<?php namespace App\Http\Controllers\Admin;

use App\Entity\Account;
use App\Entity\Repository\EntityRepository;
use App\Entity\TransactionalEmail;
use Doctrine\ORM\EntityManager;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use ScholarshipOwl\Http\JsonModel;
use ScholarshipOwl\Util\Mailer;

class TransactionalEmailController extends BaseController
{
    use ValidatesRequests;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repository;

    public function __construct(EntityManager $em)
    {
        parent::__construct();

        $this->em = $em;
        $this->repository = $em->getRepository(TransactionalEmail::class);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function TransactionalEmailAction()
    {
        $this->addBreadcrumb('Marketing', 'marketing.index');
        $this->addBreadcrumb('Transactional Emails', 'marketing.transactional_email.transactionalEmail');

        return $this->view('Marketing - Transactional Emails', 'admin.marketing.transactional_emails', [
            "transactionalEmails" => $this->repository->findAll(),
        ]);
    }

    /**
     * @param Request $request
     * @param         $id
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function saveTransactionalEmailAction(Request $request, $id)
    {
        /** @var TransactionalEmail $transactionalEmail */
        $transactionalEmail = $this->repository->findById($id);

        $this->addBreadcrumb('Marketing', "marketing.index");
        $this->addBreadcrumb('Transactional Emails', "marketing.transactional_email.transactionalEmail");
        $this->addBreadcrumb(
            $transactionalEmail->getEventName(),
            "marketing.transactional_email.saveTransactionalEmail",
            ["id" => $id]
        );

        if ($request->isMethod(Request::METHOD_POST)) {
            $this->validate($request, [
                'subject'       => 'required',
                'from_name'     => 'required',
                'from_email'    => 'required|email',

                'event_name'    => 'required',
                'template_name' => 'required',
                'sending_cap'   => 'required',
                'cap_period'    => 'required_with:cap_value',
                'cap_value'     => 'required_with:cap_period',

                'delay_type'    => 'required_with:delay_value',
                'delay_value'   => 'required_with:delay_type',

                'active'        => 'required',
            ]);

            $transactionalEmail->setSubject($request->get('subject'));
            $transactionalEmail->setFromName($request->get('from_name'));
            $transactionalEmail->setFromEmail($request->get('from_email'));

            $transactionalEmail->setEventName($request->get('event_name'));
            $transactionalEmail->setTemplateName($request->get('template_name'));
            $transactionalEmail->setSendingCap($request->get('sending_cap'));
            $transactionalEmail->setCapPeriod($request->get('cap_period'));
            $transactionalEmail->setCapValue($request->get('cap_value'));
            $transactionalEmail->setActive((bool) $request->get('active'));
            $transactionalEmail->setDelayValue($request->get('delay_value') ?: null);
            $transactionalEmail->setDelayType($request->get('delay_type') ?: null);

            $this->em->flush();

            return \Redirect::route(
                'admin::marketing.transactional_email.saveTransactionalEmail',
                $transactionalEmail->getTransactionalEmailId()
            )->with(['message' => 'Transactional Email Saved!']);
        }

        return $this->view(
            $transactionalEmail->getEventName(),
            "admin.marketing.transactional_emails_save",
            ["transactionalEmail" => $transactionalEmail]
        );
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteTransactionalEmailAction($id)
    {
        /** @var TransactionalEmail $transactionalEmail */
        $transactionalEmail = $this->repository->findById($id);

        $this->em->remove($transactionalEmail);
        $this->em->flush();

        return \Redirect::route('admin::marketing.transactional_email')->with([
            'message' => 'Transactional email removed!',
        ]);
    }

    /**
     * @param Request  $request
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function testTransactionalEmailAction(Request $request)
    {
        $this->addBreadcrumb('Marketing', 'marketing.index');
        $this->addBreadcrumb('Transactional Emails', "marketing.transactional_email.transactionalEmail");
        $this->addBreadcrumb('Email Test', 'marketing.transactional_email.testTransactionalEmail');

        if ($request->isMethod(Request::METHOD_POST)) {
            $this->validate($request, [
                'transactionalEmailId' => 'required|exists:App\Entity\TransactionalEmail',
                'accountId'            => 'required|exists:App\Entity\Account',
            ]);

            Mailer::sendMandrillTemplate(
                $this->repository->find($request->get('transactionalEmailId'))->getTemplateName(),
                $request->get('accountId')
            );

            return \Redirect::back()->with([
                'message' => 'Email successfuly sent!',
            ]);
        }

        return $this->view('Transactional Email Test', 'admin.marketing.transactional_email.test');
    }
}
