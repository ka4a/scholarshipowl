<?php

namespace App\Http\Controllers\Rest;

use App\Entity\Account;
use App\Extensions\GenericResponse;
use App\Http\Controllers\Controller;
use App\Http\Misc\Paginator;
use App\Http\Traits\JsonResponses;
use App\Rest\Traits\RestAuthorization;
use App\Services\Mailbox\EmailCount;
use App\Services\Mailbox\MailboxService;
use Doctrine\ORM\QueryBuilder;
use Illuminate\Http\Request;
use App\Services\Mailbox\Email;
class MailboxRestController extends Controller
{
    use JsonResponses;
    use RestAuthorization;

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
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $paginator = new Paginator(1000);

        try {
            $result = $this->service->fetchEmails(['folder' => $request->get('folder')], $paginator);
        } catch (\Exception $e) {
            \Log::error($e);
            $result = GenericResponse::populate([
                'meta' => [
                    'count' => 0,
                    'start' => $paginator->getOffset(),
                    'limit' => $paginator->getLimit(),
                ],
                'error'  => $e->getMessage()
            ]);
        }

        return $this->jsonSuccessResponse($result);
    }

    /**
     * Updates Email
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'isRead' => 'boolean'
        ]);

        $isReadVal = $request->input('isRead') ? 1 : 0;

        /** @var Account $account */
        $account = $this->getAuthenticatedAccount();

        if ((int)$id === 0) { // Welcome email
            if ((int)$account->getIsReadInbox() !== $isReadVal) {
                $this->authorize('update', $account);
                $account->setIsReadInbox($isReadVal);
                \EntityManager::flush($account);
            }

            return $this->jsonSuccessResponse($this->service->emulateWelcomeInboxEmail());
        }

        /** @var Email $email */
        $email = Email::populate([
            'mailbox' => $account->getUsername(),
            'email_id' => $id,
            'folder' => '',
            'subject' => '',
            'body' => '',
            'sender' => '',
            'recipient' => ''
        ]);

        $this->authorize('update', $email);

        if ($request->has('isRead')) {
            $this->service->markAsRead($email);
        }

        return $this->jsonSuccessResponse([]);
    }
}
