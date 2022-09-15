<?php

namespace App\Http\Controllers\Admin;

use App\Entity\Account;
use App\Services\Mailbox\Email;
use App\Entity\Scholarship;
use App\Facades\EntityManager;
use App\Services\Mailbox\EmailCount;
use App\Services\Mailbox\MailboxService;
use ScholarshipOwl\Http\ViewModel;

class MailboxController extends BaseController {
    /**
     * Display a single email
     * 
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
	public function emailAction($id, MailboxService $service) {
		$model = new ViewModel('admin/accounts/mailbox/email');

		$data = [
			'user' => $this->getLoggedUser(),
			'title' => 'Email',
			'active' => 'accounts',
		];

        $mailbox = request()->get('mailbox');

        /** @var \App\Services\Mailbox\Email $email */
        $email = $service->fetchEmails(['emailId' => $id, 'mailbox' => $mailbox])->getData()[0];

        $data['email'] = $email;

        /** @var Account $account */
        $account = \EntityManager::getRepository(Account::class)->findOneBy(['username' => $email->getMailbox()]);
        $data['account'] = $account;

        $data['breadcrumb'] = [
            'Dashboard' => '/admin/dashboard',
            'Accounts' => '/admin/accounts',
            'Mailbox' => "/admin/accounts/mailbox/folders/{$account->getAccountId()}",
            'Email' => '/admin/accounts/mailbox/email/$id',
        ];

        if ($scholarshipId = $email->getScholarshipId()) {
            $scholarship = \EntityManager::getRepository(Scholarship::class)->findById($scholarshipId);
            if ($scholarship) {
                $data['scholarship'] = $scholarship;
            }
        }
        
        $data['title'] = $email->getSubject();
        
		$model->setData($data);
		
		return $model->send();
	}

    /**
     * Display emails split by folder tabs
     *
     * @param $id
     * @param MailboxService $service
     * @return \Illuminate\Contracts\View\View
     */
	public function foldersAction($id, MailboxService $service) {
		$model = new ViewModel('admin/accounts/mailbox/folders');

		$data = [
			'user' => $this->getLoggedUser(),
			'breadcrumb' => [
				'Dashboard' => '/admin/dashboard',
				'Accounts' => '/admin/accounts',
				'Mailbox' => "/admin/accounts/mailbox/folders/{$id}",
			],
			'title' => 'Folders',
			'active' => 'accounts',
			'accountId' => null,
			'folders' => [
				Email::FOLDER_INBOX => [],
				Email::FOLDER_SENT => [],
			],
			'unread' => [
				Email::FOLDER_INBOX => 0,
				Email::FOLDER_SENT => 0,
			],
		];

        /** @var Account $account */
        $account = \EntityManager::getRepository(Account::class)->findOneBy(['accountId' => $id]);
        $mailbox = strtolower($account->getUsername());

        $messages = $service->fetchEmails(['mailbox' => $mailbox])->getData();
        /** @var Email $email */
        foreach ($messages as $email) {
            $data['folders'][$email->getFolder()][] = $email;
        }
        /** @var EmailCount $count */
        $count = $service->countMultiple([$mailbox], true)->getData()[$mailbox];

        $data['unread']['Inbox'] = $count->getInboxUnread();
        $data['unread']['Sent'] = $count->getSentUnread();
        $data['title'] = $account->getProfile()->getFullName();
        $data['accountId'] = $id;

		$model->setData($data);

		return $model->send();
	}
}
