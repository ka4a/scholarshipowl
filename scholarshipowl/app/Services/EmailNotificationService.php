<?php
namespace App\Services;

use Doctrine\ORM\EntityManager;
use ScholarshipOwl\Util\Mailer;

class EmailNotificationService
{

	public function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}

    /**
     * @param       $salesTeamEmail
     * @param array $accountDetails
     *   $accountDetails = [
     *      [account_id] string
     *      [name] string
     *      [email] string
     *      [phone] string
     *      [request_time] string
     *   ]
     */

	public function sendEmailNotification($salesTeamEmail, $accountDetails)
	{
        Mailer::send(
            Mailer::SYSTEM_SALES_TEAM_NOTIFICATION,
            $accountDetails,
            $salesTeamEmail,
            'Elite Membership Interest',
            ['ScholarshipOwl@scholarshipowl.com', 'ScholarshipOwl@scholarshipowl.com']
        );
	}
}