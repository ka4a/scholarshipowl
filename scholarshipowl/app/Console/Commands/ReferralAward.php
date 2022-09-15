<?php

namespace App\Console\Commands;

use App\Entity\Account;
use App\Entity\Package;
use App\Entity\ReferralAwardAccount;
use App\Entity\ReferralAwardType;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Mail;
use ScholarshipOwl\Data\Entity\Payment\SubscriptionAcquiredType;
use ScholarshipOwl\Data\Entity\Payment\SubscriptionStatus;
use ScholarshipOwl\Data\Entity\Payment\Subscription;
use ScholarshipOwl\Data\Service\IDDL;
use ScholarshipOwl\Util\Mailer;

class ReferralAward extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = "referral:award";

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = "Award referral customers.";

	/**
	 * Create a new command instance.
	 *
	 * @return void
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
        $this->info("ReferalAward Started: " . date("Y-m-d h:i:s"));

        $total = 0;

        try {
			$fileName = "ReferalAward_" . date("Y-m-d H:i:s") . ".csv";
            $file = storage_path('framework/cache') . $fileName;

			$handle = fopen($file, "w+");
			fwrite($handle, $this->getCSVLine(array("Award ID", "Award Name", "Referral Full Name", "Referral Account ID")));

            $referralAwardService = new \ScholarshipOwl\Data\Service\Account\ReferralAwardService();
            $referralAwardAccountService = new \ScholarshipOwl\Data\Service\Account\ReferralAwardAccountService();
            $subscriptionService = new \ScholarshipOwl\Data\Service\Payment\SubscriptionService();

            $awards = $referralAwardService->getReferralAwards(true);

            foreach ($awards as $award) {
                $sql = "";

                if ($award->getReferralAwardType()->getReferralAwardTypeId() == ReferralAwardType::NUMBER_OF_REFERRALS){
                    $sql = sprintf("SELECT
                        r.referred_account_id
                    FROM
                        referral r
                    WHERE r.referred_account_id NOT IN (SELECT account_id FROM referral_award_account WHERE award_type = '%s' AND referral_award_id = ?)
                    GROUP BY r.referred_account_id
                    HAVING COUNT(*) >= (SELECT
                            referrals_number
                        FROM
                            referral_award ra
                        WHERE
                            referral_award_id = ?);", ReferralAwardAccount::REFERRED_AWARD);
                } else if($award->getReferralAwardType()->getReferralAwardTypeId() == ReferralAwardType::NUMBER_OF_PAID_REFERRALS){
                    $sql = sprintf("SELECT
                        r.referred_account_id
                    FROM
                        referral r
                            LEFT JOIN
                        referral_award_account raa ON r.referred_account_id = raa.account_id
                            LEFT JOIN
                        subscription s ON r.referral_account_id = s.account_id
                    WHERE
                        r.referred_account_id NOT IN (
                            SELECT account_id
                            FROM referral_award_account
                            WHERE award_type = '%s' AND referral_award_id = ?
                        )
                        AND
                        r.referred_account_id IN (
                            SELECT DISTINCT s.account_id
                            FROM subscription AS s
                            JOIN `transaction` AS t ON t.subscription_id = s.subscription_id
                        )
                    GROUP BY (r.referred_account_id)
                    HAVING COUNT(DISTINCT s.account_id) >= (SELECT
                            referrals_number
                        FROM
                            referral_award ra
                        WHERE
                            referral_award_id = ?);", ReferralAwardAccount::REFERRED_AWARD);
                } else {
                    continue;
                }

                $resultSet = \DB::select($sql, array($award->getReferralAwardId(), $award->getReferralAwardId()));

                foreach($resultSet as $row){
                    $referralAwardAccountService->saveReferralAwardAccount(
                        $row->referred_account_id,
                        $award->getReferralAwardId(),
                        ReferralAwardAccount::REFERRED_AWARD
                    );
                    \PaymentManager::applyPackageOnAccount(
                        \EntityManager::findById(Account::class, $row->referred_account_id),
                        \EntityManager::findById(Package::class, $award->getReferredPackage()->getPackageId()),
                        \App\Entity\SubscriptionAcquiredType::REFERRED
                    );

                    // Fetch Data For Report
                    $sql = sprintf("
                        SELECT
                            p.first_name, p.last_name, p.account_id
                        FROM %s AS p
                        left join account a on p.account_id = a.account_id
                        WHERE p.account_id  = %d and a.sell_information !=1
                    ", IDDL::TABLE_PROFILE, $row->referred_account_id);
                    $resultSet = \DB::select(\DB::raw($sql));
                    foreach ($resultSet as $row) {
                        $data = $this->getCSVLine(array(
                            $award->getReferralAwardId(),
                            $award->getName(),
                            $row->first_name . " " . $row->last_name,
                            $row->account_id
                        ));

                        fwrite($handle, $data);
                    }
                }
                $total += count($resultSet);
            }

            fclose($handle);

            Mail::send(new \App\Mail\ReferralAward(['total' => $total], $file, $fileName));

            unlink($file);
        } catch(\Exception $exc) {
            \Log::error($exc);
        }

        $this->info("ReferralAward Ended: " . date("Y-m-d h:i:s"));
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}


    private function getCSVLine($data) {
        $result = array();

        foreach($data as $value) {
            $result[] = "\"" . $value . "\"";
        }

        return implode(",", $result) . PHP_EOL;
    }
}
