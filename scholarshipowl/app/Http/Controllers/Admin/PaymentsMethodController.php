<?php namespace App\Http\Controllers\Admin;

use App\Entity\BraintreeAccount;
use App\Entity\FeatureCompanyDetailsSet;
use App\Entity\PaymentMethod;
use App\Entity\Repository\EntityRepository;
use Doctrine\ORM\EntityManager;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class PaymentsMethodController extends BaseController
{
    use ValidatesRequests;
    /**
     * @var EntityManager $em
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * PaymentsMethodController constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct();
        $this->em = $em;
        $this->repository = $this->em->getRepository(PaymentMethod::class);
    }

    public function paymentMethodsAction()
    {
        /** @var PaymentMethod[] $paymentMethodList */
        $paymentMethodList = $this->repository->findBy([], ['id' => 'ASC']);
        return $this->view('Payment methods', 'admin.payments.payment_methods.index', [
            'methods_list' => $paymentMethodList
        ]);
    }

    /**
     * @param Request $request
     * @param null    $id
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function editPaymentMethodsAction(Request $request, $id = null)
    {
        /** @var PaymentMethod $paymentMethod */
        $paymentMethod = $id !== null ? $this->repository->findById($id) : null;

        if ($paymentMethod && $request->isMethod(Request::METHOD_POST)) {
            $this->validate($request, [
                'company_details_id' => 'required',
            ]);

            $paymentMethod->setFeatureCompanyDetailsSet($request->get('company_details_id'));

            $this->em->flush($paymentMethod);

            return \Redirect::route('admin::payments.payment_methods.index', $paymentMethod->getId())
                ->with('message', sprintf('Payment method \'%s\' saved!', $paymentMethod->getName()));
        }

        $this->addBreadcrumb('Payment Methods', 'payments.payment_methods.index');

        return $this->view('Edit payment method', 'admin.payments.payment_methods.edit', [
            'set' => $paymentMethod,
            'companyDetailsList' => FeatureCompanyDetailsSet::options()
        ]);
    }
}
