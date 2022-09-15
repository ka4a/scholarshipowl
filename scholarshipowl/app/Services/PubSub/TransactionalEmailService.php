<?php

namespace App\Services\PubSub;

use App\Entity\Account;
use App\Entity\TransactionalEmail;
use App\Entity\TransactionalEmailSend;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Google\Cloud\PubSub\PubSubClient;
use Google\Cloud\PubSub\Topic;

class TransactionalEmailService extends AbstractPubSubService
{
    const USER_ABANDONED_APPLICATION_PROCESS = "user.abandoned_application_process";
    const USER_APPLY_FREE = "user.apply_free";
    const USER_APPLY_PAID = "user.apply_paid";
    const USER_ACCOUNT_UPDATE = "user.account_update";
    const USER_APPLICATION = "user.application"; // NOT USED
    const USER_FORGOT_PASSWORD = "user.forgot_password";
    const USER_MAILBOX_WELCOME = "user.mailbox_welcome";
    const USER_PACKAGE_EXHAUSTED = "user.package_exhausted";
    const USER_PACKAGE_PURCHASE = "user.package_purchase";
    const USER_REGISTER = "user.register";
    const USER_CHANGE_PASSWORD = "user.change_password";
    const USER_REFER_FRIEND = "user.refer_friend";
    const USER_RECURRENT_SCHOLARSHIPS_NOTIFY = "user.recurrent_scholarship_notify";

    const SINGLE_SUCCESSFUL_DEPOSIT = "mandrill-successful-non-recurrent-deposit";
    const INITIAL_SUCCESSFUL_DEPOSIT = "mandrill-1st-successful-deposit";
    const MANDRILL_RECURRENT_SUCCESSFUL_DEPOSIT = "mandrill-successful-repeat-deposit";
    const MANDRILL_SINGLE_FAILED_DEPOSIT = "mandrill-1st-failed-deposit";
    const MANDRILL_INITIAL_FAILED_DEPOSIT = "mandrill-failed-recurrent-deposit";
    const MANDRILL_RECURRENT_FAILED_DEPOSIT = "mandrill-failed-recurrent-deposit";
    const MANDRILL_SINGLE_SUBSCRIPTION_CREATED = "mandrill-membership-awarded-one-time-billing";
    const MANDRILL_INITIAL_SUBSCRIPTION_CREATED = "mandrill-membership-awarded-repeat-billing";
    const RECURRENT_SUBSCRIPTION_CREATED = "mandrill-membership-renewed-repeat-billing";
    const RECURRENT_SUBSCRIPTION_EXPIRED = "mandrill-membership-expired";
    const SINGLE_SUBSCRIPTION_EXPIRED = "mandrill-membership-expired";
    const MEMBERSHIP_EXPIRED = "membership-expired";
    const MANDRILL_APPLICATIONS_SENT = "mandrill-application-s-sent-succesfully";
    const APPLICATIONS_EXPIRE_48H = "mandrill-application-s-expire-within-48h";
    const MANDRILL_ABANDONED_APPLICATION_PROCESS = "abandoned-application-process";
    const MANDRILL_APPLY_FREE = "apply-free";
    const MANDRILL_APPLY_PAID = "apply-paid";
    const ACCOUNT_UPDATE = "account-update";
    const FORGOT_PASSWORD = "forgot-password";
    const MANDRILL_FORGOT_PASSWORD_APPLY_ME = "forgot-password-apply-me";
    const MANDRILL_MAILBOX_WELCOME = "mailbox-welcome";
    const MANDRILL_MAILBOX_WELCOME_APPLY_ME = "mailbox-welcome-apply-me";
    const PACKAGE_EXHAUSTED = "package-exhausted";
    const PACKAGE_PURCHASE = "purchase-package";
    const MANDRILL_REGISTER = "register";
    const MANDRILL_CHANGE_PASSWORD = "change-password";
    const REFER_FRIEND = "refer-a-friend";
    const ACCOUNT_WELCOME = "account-welcome";
    const MANDRILL_ACCOUNT_WELCOME_APPLY_ME = "account-welcome-apply-me";
    const YDIT_CONFIRMATION = "you-deserve-it-confirmation";
    const MANDRILL_YDIT_CONFIRMATION_APPLY_ME = "you-deserve-it-confirmation-apply-me";
    const NEW_EMAIL = "new-email";
    const MANDRILL_NEW_EMAIL_APPLY_ME = "new-email-apply-me";
    const NEW_ELIGIBLE_SCHOLARSHIPS = "new-eligible-scholarships";
    const RECURRENT_SCHOLARSHIPS_NOTIFY = "recurrent-scholarships-notify";

    const FREETRIAL_ACTIVATED = 'free-trial-activated';
    const FREETRIAL_CANCELLED = 'free-trial-cancelled';
    const FREETRIAL_FIRST_CHARGE = 'first-charge-from-free-trial';

    const SUBSCRIPTION_CREDIT_EXHAUSTED = 'subscription-credit-exhausted';
    const SUBSCRIPTION_CREDIT_INCREASES = 'subscription-credit-increases';

    const SUBSCRIPTION_UPCOMING = 'subscription-and-payment-renewal';
    const SUBSCRIPTION_ACTIVATED = 'subscription-activated';

    const SELECT_PASSWORD = "select-password";
    const CHANGE_PASSWORD = "change-password";

    const SYSTEM_CONTACT = "system.contact";
    const SYSTEM_EXCEPTION = "system.exception";
    const SYSTEM_SCHOLARSHIPS_EXPIRE = "system.scholarships_expire";
    const SYSTEM_SUBSCRIPTIONS_EXPIRE = "system.subscriptions_expire";
    const SYSTEM_SUBSCRIPTIONS_RENEW = "system.subscriptions_renew";
    const SYSTEM_REFERRAL_AWARD = "system.referral_award";
    const SYSTEM_REGISTER = "system.register";

    const SYSTEM_RECURRENT_SCHOLARSHIPS_NOTIFY = "system.recurrent_scholarship_notify";

    const SYSTEM_SALES_TEAM_NOTIFICATION = "system.sales_team-notification";

    const SCHOLARSHIP_USER_WON = "sunrise-scholarship-user-won";
    const SCHOLARSHIP_USER_AWARDED = "sunrise-scholarship-user-awarded";
    const SCHOLARSHIP_USER_MISSED = "sunrise-scholarship-user-missed";
    const SCHOLARSHIP_WINNER_CHOSEN = "sunrise-scholarship-winner-chosen";

    const APP_PASSWORD_RESET = "app-password-reset";
    const APP_MEMBERSHIP_INVITE = "app-membership-invite";
    const APP_MAGIC_LINK = "app-magic-link";

    const APPLICATION_SENT  = "application-sent";


    /**
     * @var PubSubClient
     */
    protected $pubSubClient;

    protected $defaultTopic = 'sowl.transEmail';

    /**
     * @var Topic
     */
    protected $topic;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * TransactionalEmailService constructor.
     *
     * @param PubSubClient  $pubSubClient
     * @param EntityManager $em
     */
    public function __construct(PubSubClient $pubSubClient, EntityManager $em)
    {
        $this->em = $em;
        $this->pubSubClient = $pubSubClient;
        $this->topic = $pubSubClient->topic('sowl.transEmail');
    }

    /**
     * @return PubSubClient
     */
    public function getPubSubClient()
    {
        return $this->pubSubClient;
    }


    /**
     * @param Account|\ScholarshipOwl\Data\Entity\Account\Account $account
     * @param string $template
     * @param array $data
     * @param array $attributes the other information on the message to send
     *
     * @throws \Exception
     */
    public function sendCommonEmail($account, $template, $data = [], $attributes = [])
    {
        $transactionalEmail = $this->checkIfTransactionEmailExist($template);
        if ($transactionalEmail->isActive()) {
            $attributes = $this->generateAttributes($account, $template, $attributes);
            if ($this->isSendingCap($account, $transactionalEmail)) {
                try {
                    $this->publishMessage(json_encode($data), $attributes);
                } catch (\Exception $e) {
                    \Sentry::captureException($e);
                    \Log::error("Transaction email service error occurred: " . get_class($e). " - " . $e->getMessage());
                }
            } else {
                \Log::info(
                    sprintf('Transactional email skipped because of exhausted capacity. PubSub message data: %s attributes: %s',
                        json_encode($data), json_encode($attributes)
                    )
                );
            }
        }
    }

    /**
     * @param array $accounsList
     * @param string $template
     * @param array   $data
     *
     * @throws \Exception
     */
    public function sendBulkCommonEmail($accounsList, $template, $data = []){
        $transactionalEmail = $this->checkIfTransactionEmailExist($template);

        if ($transactionalEmail->isActive()) {
            $qb = $this->em->createQueryBuilder();
            $query = $this->em->getRepository(Account::class)
                ->createQueryBuilder('a')
                ->addSelect(['emails.sending_cap' , 'emails.cap_value'])
                ->leftJoin(
                    TransactionalEmail::class,
                    'emails',
                    Query\Expr\Join::WITH,
                    'emails.transactionalEmailId = :transactionalEmail'
                )
                ->leftJoin(
                    TransactionalEmailSend::class,
                    'email_send',Query\Expr\Join::WITH,
                    'email_send.account = a and email_send.transactional_email = emails.transactionalEmailId'
                )
                ->where('a IN (:accountIdsList)')
                ->andWhere($qb->expr()->orX(
                    'email_send.send_date > :date',
                    'email_send.send_date IS NULL'
                ))
                ->having('emails.sending_cap = 0 OR count(DISTINCT email_send.transactional_email_send_id) < emails.cap_value')
                ->groupBy('a.accountId')
                ->setParameter('transactionalEmail', $transactionalEmail)
                ->setParameter('accountIdsList', $accounsList)
                ->setParameter('date', Carbon::now()->sub($transactionalEmail->getCapInterval()))
                ->getQuery();

            if (!empty($accounts = array_map('current', $query->getResult()))) {
                try {
                    foreach ($accounts as $account){
                        $attributes = $this->generateAttributes($account, $template);
                        $this->publishMessage(json_encode($data), $attributes);
                    }
                } catch (\Exception $e) {
                    \Sentry::captureException($e);
                    \Log::error("Transaction email service error occurred: " . get_class($e). " - " . $e->getMessage());
                }
            }
        }
    }

    /**
     * @param Account|\ScholarshipOwl\Data\Entity\Account\Account $account
     * @param string $templateName
     * @param array $extraAttributes
     *
     * @return array
     */
    protected function generateAttributes($account, $templateName, array $extraAttributes = []){
        $baseAttributes = [
            'accountId' => strval($account->getAccountId()),
            'template' => $templateName
        ];
        return array_merge($baseAttributes, $extraAttributes);
    }

    /**
     * Switch default topic to other
     * @param string $topicName
     *
     * @return $this
     */
    public function setTopic($topicName = 'sowl.transEmail'){
        $this->topic = $this->pubSubClient->topic($topicName);
        return $this;
    }

    /**
     * @param null $topicName
     *
     * @return Topic
     */
    protected function getTopic($topicName = null){
        if(!is_null($topicName)){
            $this->setTopic($topicName);
        }
        return $this->topic;
    }

    /**
     * @param string $data
     * @param array $attributes
     *
     * @return array
     * @throws \Exception
     */
    protected function publishMessage(string $data = null, array $attributes = null){
        if(!in_array('accountId', array_keys($attributes))){
            throw new \Exception('Attributes list should contains accountId field');
        }

        $result =  $this->getTopic()->publish([
            'data' => $data,
            'attributes' => $attributes
        ]);

        \Log::info(
            "Transactional email message sent to PubSub, data: {$data} attributes: ".json_encode($attributes)
        );

        return $result;
    }

    /**
     * Store to log table record about sent email
     * @param TransactionalEmail $transactionalEmail
     * @param Account|\ScholarshipOwl\Data\Entity\Account\Account            $account
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function storeAccountsTransactionEmail(TransactionalEmail $transactionalEmail, $account){
        $transactionalEmailSend = new TransactionalEmailSend($transactionalEmail, $account);
        $this->em->persist($transactionalEmailSend);
        $this->em->flush($transactionalEmailSend);
    }

    /**
     * @param Account|\ScholarshipOwl\Data\Entity\Account\Account $account
     * @param TransactionalEmail $transactionalEmail
     *
     * @return bool
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function isSendingCap($account, $transactionalEmail)
    {
        $send = true;
        if ($transactionalEmail->getSendingCap()) {
            $count = $this->em->createQueryBuilder()
                ->select('count(DISTINCT TransactionalEmailSend.transactional_email_send_id)')
                ->from(TransactionalEmailSend::class, 'TransactionalEmailSend')
                ->join('TransactionalEmailSend.transactional_email',
                    'TransactionalEmail')
                ->where('TransactionalEmailSend.account = :accountId')
                ->andWhere('TransactionalEmailSend.transactional_email = :transactionalEmail')
                ->andWhere('TransactionalEmailSend.send_date > :date')
                ->setParameter('transactionalEmail', $transactionalEmail)
                ->setParameter('accountId', $account->getAccountId())
                ->setParameter('date',
                    Carbon::now()->sub($transactionalEmail->getCapInterval()))
                ->getQuery()->getSingleScalarResult();

            if ($count >= $transactionalEmail->getSendingCap()) {
                $send = false;
            }
        }

        return $send;
    }

    /**
     * Check if template exist and configured in system
     * @param $template
     *
     * @return TransactionalEmail
     */
    protected function checkIfTransactionEmailExist($template) {
        $repository = $this->em->getRepository(TransactionalEmail::class);
        /** @var TransactionalEmail $transactionalEmail */
        if (null === ($transactionalEmail = $repository->findOneBy(
                ['template_name' => $template]
            ))
        ) {
            throw new \InvalidArgumentException(
                sprintf('Template "%s" not found!', $template)
            );
        }

        return $transactionalEmail;
    }

}
