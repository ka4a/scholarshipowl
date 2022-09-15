<?php namespace App\Services;

use App\Entity\Account;
use App\Entity\Domain;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use App\Entity\TransactionalEmail;
use App\Entity\TransactionalEmailSend;
use App\Services\Mailbox\EmailCount;
use App\Services\Mailbox\MailboxService;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use ScholarshipOwl\Data\DateHelper;
use ScholarshipOwl\Util\Mailer;

class MandrillEmailService
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    protected $templatesMapping = [
            Domain::APPLYME => [
                'mailbox-welcome'             => Mailer::MANDRILL_MAILBOX_WELCOME_APPLY_ME,
                'account-welcome'             => Mailer::MANDRILL_ACCOUNT_WELCOME_APPLY_ME,
                'you-deserve-it-confirmation' => Mailer::MANDRILL_YDIT_CONFIRMATION_APPLY_ME,
                'new-email'                   => Mailer::MANDRILL_NEW_EMAIL_APPLY_ME
            ]
        ];

    public function sendBulkTemplates($template, $accountList, $data = [])
    {
        if (is_testing()) {
            return false;
        }

        $repository = $this->em->getRepository(
            TransactionalEmail::class
        );
        /** @var TransactionalEmail $transactionalEmail */
        if (null === ($transactionalEmail = $repository->findOneBy(
                ['template_name' => $template]
            ))
        ) {
            throw new \InvalidArgumentException(
                sprintf('Template "%s" not found!', $template)
            );
        }

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
                ->setParameter('accountIdsList', $accountList)
                ->setParameter('date', Carbon::now()->sub($transactionalEmail->getCapInterval()))
                ->getQuery();

            if (!empty($accounts = array_map('current', $query->getResult()))) {
                try {
                    $this->sendTemplateToAccount($template, $accounts, $transactionalEmail, $data, true);
                } catch (\Exception $e) {
                    \Sentry::captureException($e);
                    \Log::error("A mandrill error occurred: " . get_class($e). " - " . $e->getMessage());
                }
            }
        }
    }


    /**
     * @param       $template
     * @param       $accountId
     * @param array $data
     * @param null  $to
     * @param null  $subject
     * @param null  $from
     *
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function sendTemplate($template, $accountId, $data = [], $to = null, $subject = null, $from = null) {
        if (is_testing()) {
            return false;
        }

        $repository = $this->em->getRepository(TransactionalEmail::class);
        $send = true;

        /** @var TransactionalEmail $transactionalEmail */
        if (null === ($transactionalEmail = $repository->findOneBy(['template_name' => $template]))
        ) {
            throw new \InvalidArgumentException(
                sprintf('Template "%s" not found!', $template)
            );
        }

        if ($transactionalEmail->isActive()) {
            if ($transactionalEmail->getSendingCap()) {
                $count = $this->em->createQueryBuilder()
                    ->select('count(DISTINCT TransactionalEmailSend.transactional_email_send_id)')
                    ->from(TransactionalEmailSend::class,'TransactionalEmailSend')
                    ->join('TransactionalEmailSend.transactional_email','TransactionalEmail')
                    ->where('TransactionalEmailSend.account = :accountId')
                    ->andWhere('TransactionalEmailSend.transactional_email = :transactionalEmail')
                    ->andWhere('TransactionalEmailSend.send_date > :date')
                    ->setParameter('transactionalEmail', $transactionalEmail)
                    ->setParameter('accountId', $accountId)
                    ->setParameter('date', Carbon::now()->sub($transactionalEmail->getCapInterval()))
                    ->getQuery()->getSingleScalarResult();

                if ($count >= $transactionalEmail->getSendingCap()) {
                    $send = false;
                }
            }

            if ($send) {
                /** @var Account $account */
                $account = $this->em->find(Account::class, $accountId);

                try {
                    $this->sendTemplateToAccount(
                        $template,
                        $account,
                        $transactionalEmail,
                        $data,
                        false,
                        $to,
                        $from,
                        $subject
                    );
                } catch (\Exception $e) {
                    \Sentry::captureException($e);
                    \Log::error("A mandrill error occurred: " . get_class($e). " - " . $e->getMessage());
                }
            }
        }
    }

    /**
     * Send template with additional data to email
     *
     * @param      $template
     * @param array | Account | Account[] $accounts array of accounts to send
     * @param TransactionalEmail $transactionalEmail
     * @param      $data
     * @param bool $async
     * @param      $to
     * @param      $from
     * @param      $subject
     *
     * @return bool
     */
    protected function sendTemplateToAccount($template, $accounts, $transactionalEmail, $data, $async = false, $to = null, $from = null, $subject = null) {
        if (!is_array($accounts)) {
            $accounts = [$accounts];
        }

        if (empty($accounts)) {
            throw new \RuntimeException("Mailer: to is empty");
        }

        $subject = is_null($subject) ? $transactionalEmail->getSubject() : $subject;
        $from = is_null($from) ? [$transactionalEmail->getFromEmail(), $transactionalEmail->getFromName()] : $from;

        if (empty($from)) {
            throw new \RuntimeException("Mailer: from is empty");
        }


        $template_content = [
            [
                "name"    => "main",
                "content" => "example content"
            ]
        ];

        /**
         * @var Account $account
         */
        foreach ($accounts as $account) {

            $recipient = $to;
            if (empty($to)) {
                $recipient = $account->getEmail();
            }
            $mergeVars = $this->generateMergeVars($data, $account);

            if ($account->getDomain()->is(Domain::APPLYME)) {
                if (!isset($this->templatesMapping[Domain::APPLYME][$template])) {
                    return false;
                }

                $template
                    = $this->templatesMapping[Domain::APPLYME][$template];
            }

            $message = [
                "subject"        => $subject,
                "from_email"     => $from[0],
                "from_name"      => $from[1],
                "to"             => [
                    [
                        "email" => $recipient,
                        "type"  => "to"
                    ]
                ],
                "track_opens"    => true,
                "track_clicks"   => true,
                "merge"          => true,
                "merge_language" => "mailchimp",
                "merge_vars"     => [
                    [
                        "rcpt" => $recipient,
                        "vars" => $mergeVars
                    ]
                ],
            ];

            $sendAt = null;
            if ($interval = $transactionalEmail->getDelayInterval()) {
                $sendAt = Carbon::now('UTC')->add($interval)->format(
                    DateHelper::DEFAULT_FORMAT
                );
            }

            if (is_production()) {
                $mandrill = new \Mandrill(config('scholarshipowl.mail.mandrill.api_key'));
                $mandrill->messages->sendTemplate(
                    $template,
                    $template_content,
                    $message,
                    $async,
                    null,
                    $sendAt
                );
            } else {
                \Log::info(sprintf('Transaction email `%s` sent to %s', $template, $account->getAccountId()));
            }

            $transactionalEmailSend = new TransactionalEmailSend($transactionalEmail, $account);
            $this->em->persist($transactionalEmailSend);
            $this->em->flush($transactionalEmailSend);
        }
    }

    /**
     * @param $data
     * @param Account $account
     *
     * @return array
     */
    protected function generateMergeVars($data, $account)
    {
        $mergeVars = [];

        if ($account) {
            /** @var ScholarshipRepository $scholarshipsRepo */
            $scholarshipsRepo = $this->em->getRepository(Scholarship::class);
            $elschoc = $scholarshipsRepo->countEligibleScholarships($account);

            /** @var MailboxService $mailboxService */
            $mailboxService = app(MailboxService::class);
            /** @var EmailCount $mailboxData */
            $mailboxData = $mailboxService->countEmails($account)->getData();
            $username = $account->getUsername();
            $unread = $mailboxData->getInboxUnread();

            $mergeVars[] = ["name" => "FNAME", "content" => $account->getProfile()->getFirstName()];
            $mergeVars[] = ["name" => "LNAME", "content" => $account->getProfile()->getLastName()];
            $mergeVars[] = ["name" => "ELSCHOC", "content" => $elschoc];
            $mergeVars[] = ["name" => "UNREADM", "content" => $unread];
        }

        foreach ($data as $key => $value) {
            $mergeVars[] = ["name" => $key, "content" => $value];
        }

        return $mergeVars;
    }
}

