<?php

namespace App\Http\Controllers\Index;


use App\Http\Controllers\Api\MailboxDBController;
use App\Services\Mailbox\MailboxService;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MailboxController extends BaseController
{
    use AuthorizesRequests;

    /**
     * @var MailboxService
     */
    protected $service;

    /**
     * MailboxRestController constructor.
     * @param MailboxService $service
     */
    public function __construct(MailboxService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    /**
     * @param $id
     *
     * @return string
     */
    public function html($id)
    {
        /** @var Email $email */

        if ($id == 0) {
            $email = $this->service->emulateWelcomeInboxEmail();
        } else {
            $email = $this->service->fetchEmailById($id);
        }

        $this->authorize('show', $email);

        $body = trim($email->getBody());
        $body = str_replace('<a', '<a target="_blank"', $body);

        if (!preg_match("#(?<=<)\w+(?=[^<]*?>)#", $body)) {
            $body = nl2br($body);
        }

        return $body;
    }
}
