<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Connection;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use ScholarshipOwl\Data\Entity\Payment\Package;
use ScholarshipOwl\Data\Service\IDDL;

use ScholarshipOwl\Domain\Log\PaymentMessage;
use ScholarshipOwl\Domain\Payment\PaymentFactory;
use ScholarshipOwl\Domain\Payment\IMessage;

class SubscriptionInfo extends Command
{

    const OPTION_DUPLICATE_REMOVE = 'duplicate-remove';

    const OPTION_EXTERNAL_ID_FIX = 'fix-external-id';

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'subscription:info';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
        /** @var Connection $db */
        $db = \DB::getFacadeRoot()->connection();

        $query = $db->table('subscription AS s')
            ->leftJoin('transaction AS t', 't.subscription_id', '=', 's.subscription_id')
//            ->where('s.subscription_status_id', '=', 1)
            ->where('s.price', '>', 1);

        $countQuery = clone $query;
        $this->info(sprintf("Found %s not free subscriptions", $countQuery->count('s.subscription_id')));

        $subscriptionWithoutTransactionsQuery = clone $query;
        $subscriptionWithoutTransactions = $subscriptionWithoutTransactionsQuery
            ->whereNull('t.transaction_id')->get();
        $this->{count($subscriptionWithoutTransactions) ? 'error' : 'info'}(
            sprintf("Subscriptions without transactions: %s", count($subscriptionWithoutTransactions))
        );

        $recurrent = array();
        $recurrentWithoutExternalId = array();

        $subscriptions = array();
        $subscriptionsDuplicate = array();
        $subscriptionsRecurrentDuplicate = array();
        $transactionsDuplicate = array();

        $account = array();

        $allQuery = clone $query;
        $result = $allQuery
//            ->whereNotNull('t.transaction_id')
//            ->orderBy('s.subscription_id', 'desc')
            ->get(array(
                '*' => 's.*',
//                'subscription_id' => 's.subscription_id',
//                'expiration_type' => 's.expiration_type',
//                'external_id' => 's.external_id',
//                'account_id' => 's.account_id',
//                'package_id' => 's.package_id',

                'transaction_id' => 't.transaction_id',
                'response_data' => 't.response_data',
                'bid' => 't.bank_transaction_id',
                'pid' => 't.provider_transaction_id',
            ));
//        die($query->toSql());
//        die(var_export($result[0], true));

        /** @var \StdClass $subscription */
        foreach ($result as $subscription) {

            if (isset($subscription->subscription_id)) {
                $id = $subscription->subscription_id;
                $tid = $subscription->transaction_id;

                if (!empty($subscription->external_id)) {
                    if (!isset($recurrent[$subscription->external_id])) {
                        $recurrent[$subscription->external_id] = $subscription;
                    } else {
                        $subscription->duplicate_of = $recurrent[$subscription->external_id];
                        $subscriptionsRecurrentDuplicate[] = $subscription;
                    }
                } elseif ($subscription->expiration_type === Package::EXPIRATION_TYPE_RECURRENT &&
                    $subscription->subscription_acquired_type_id !== 6 &&
                    $subscription->name !== '5Friends'
                ) {
                    $recurrentWithoutExternalId[] = $subscription;
                }

                if (!isset($subscriptions[$id])) {
                    $subscriptions[$id] = $subscription;
                } else {
                    if ($tid) {
                        $transactionsDuplicate[] = $tid;
                    }
                }

                if ($tid) {
                    $uniqueKey = implode('-', array(
                        $subscription->account_id,
                        $subscription->bank_transaction_id,
                        $subscription->provider_transaction_id,
                    ));

                    if (!isset($account[$uniqueKey])) {
                        $account[$uniqueKey] = $subscription;
                    } elseif (!in_array($subscription, $subscriptionsRecurrentDuplicate)) {
                        $subscription->duplicate_of = $account[$uniqueKey];
                        $subscriptionsDuplicate[] = $subscription;
                    }
                }

            }
        }

        $this->{count($recurrentWithoutExternalId) ? 'error' : 'info'}(
            sprintf("Recurrent without external id: %s", count($recurrentWithoutExternalId))
        );

        $this->{count($subscriptionsRecurrentDuplicate) ? 'error' : 'info'}(
            sprintf("Duplicate recurrent subscription found: %s", count($subscriptionsRecurrentDuplicate))
        );

        $this->{count($subscriptionsDuplicate) ? 'error' : 'info'}(
            sprintf("Duplicate subscriptions found: %s", count($subscriptionsDuplicate))
        );

        $this->{count($transactionsDuplicate) ? 'error' : 'info'}(
            sprintf("Duplicate transactions found: %s", count($transactionsDuplicate))
        );

        if ($this->option(static::OPTION_DUPLICATE_REMOVE)) {
            $this->deleteTransactions($transactionsDuplicate, $db);
            $this->deleteSubscriptions($subscriptionsDuplicate, $db);
            $this->deleteSubscriptions($subscriptionsRecurrentDuplicate, $db);
        }

        if ($this->option(static::OPTION_EXTERNAL_ID_FIX)) {
            $this->fixExternalId($recurrentWithoutExternalId, $db);
        }

	}

    protected function fixExternalId(array $subscriptions, Connection $db)
    {
        foreach ($subscriptions as $subscription) {

            if ($subscription->transaction_id) {

                $transaction = $db->table(IDDL::TABLE_TRANSACTION)
                    ->where('transaction_id', '=', $subscription->transaction_id)
                    ->first();

                $message = PaymentFactory::createMessage(json_decode($subscription->response_data, true), $transaction->payment_method_id);
                $this->fixSubscriptionExternalId($db, $subscription->subscription_id, $message);


            } else {

                $this->info(sprintf(
                    'Subscription without transaction_id. Subscription "%s" (%s) Account: %s Package: %s Method: %s',
                    $subscription->name,
                    $subscription->subscription_id,
                    $subscription->account_id,
                    $subscription->package_id,
                    $subscription->payment_method_id
                ));

                if ($this->confirm("Delete subscription? [y/N]")) {
                    $db->delete('DELETE FROM subscription WHERE subscription_id = ?', array($subscription->subscription_id));
                    continue;
                }

                // Get all external_id of user to exclude them in SQL
//                    $externalIds = $db->table(IDDL::TABLE_SUBSCRIPTION)->where('')
                switch ($subscription->payment_method_id) {

                    case \ScholarshipOwl\Data\Entity\Payment\PaymentMethod::CREDIT_CARD:
                        $messageLike = 'customField1":"' . $subscription->package_id . '%';
                        $messageLike .= 'customField2":"' . $subscription->account_id;
                        break;

                    case \ScholarshipOwl\Data\Entity\Payment\PaymentMethod::PAYPAL:
                        $messageLike = $subscription->package_id .'_'. $subscription->account_id .'_';
                        break;

                    default:
                        $messageLike = $subscription->account_id;
                        break;
                }


                $excludeExternalIds = '';
                if ($externalIds = $db->select('SELECT external_id FROM subscription WHERE account_id = ? AND external_id IS NOT NULL', array($subscription->account_id))) {
                    foreach ($externalIds as $row) {
                        if ($row->external_id) {
//                            $excludeExternalIds .= ' AND message NOT LIKE "%' .$row->external_id. '%"';
                        }
                    }
                }

                $binding = array();
                $query = 'SELECT * FROM log_payment_message WHERE message LIKE "%' . $messageLike . '%" ' . $excludeExternalIds;
                $this->info(sprintf('SQL: %s', $query));
                if ($result = $db->select($query, $binding)) {

                    $this->info(sprintf('Found %s possible payment messages.', count($result)));

                    foreach ($result as $row) {

                        $this->info(var_export($row, true));
                        if ($this->confirm(sprintf("Does this correct message for subscription?"))) {

                            $message = PaymentMessage::getPaymentMessage($row->log_payment_message_id);
                            $this->fixSubscriptionExternalId($db, $subscription->subscription_id, $message);

                            break;
                        }

                    }

                } else {
                    throw new \Exception(sprintf("Can't find payment message for %s", $subscription->subscription_id));
                }
            }
        };

    }

    private function fixSubscriptionExternalId(Connection $db, $subscriptionId, IMessage $message)
    {
        try {

            $db->beginTransaction();

            $db->update('UPDATE subscription SET payment_method_id = ?, external_id = ? WHERE subscription_id = ?', array(
                $message->getPaymentMethod(),
                $message->getExternalSubscriptionId(),
                $subscriptionId,
            ));

            $db->commit();

        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /**
     * @param array $subscriptions
     * @param Connection $db
     * @throws Exception
     */
    protected function deleteSubscriptions(array $subscriptions, Connection $db)
    {
        try {

            $db->beginTransaction();

            foreach ($subscriptions as $subscription) {
                $subscriptionId = $subscription->subscription_id;

                $db->update('UPDATE application SET subscription_id = ? WHERE subscription_id = ?',array(
                    $subscription->duplicate_of->subscription_id,
                    $subscriptionId,
                ));

                $db->delete('DELETE FROM transaction WHERE subscription_id = ?', array($subscriptionId));
                $db->delete('DELETE FROM subscription WHERE subscription_id = ?', array($subscriptionId));
            };


            $db->commit();

        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /**
     * @param array $transactionsIds
     * @param Connection $db
     * @throws Exception
     */
    protected function deleteTransactions(array $transactionsIds, Connection $db)
    {
        try {

            $db->beginTransaction();

            foreach ($transactionsIds as $transactionId) {
                $db->delete('DELETE FROM transaction WHERE transaction_id = ?', array($transactionId));
            }

            $db->commit();

        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
//			array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array(static::OPTION_DUPLICATE_REMOVE, null, InputOption::VALUE_NONE, 'Remove duplicate subscriptions', null),
            array(static::OPTION_EXTERNAL_ID_FIX, null, InputOption::VALUE_NONE, 'Fix external id for recurrent', null),
		);
	}

}
