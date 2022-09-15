<?php namespace App\Http\Controllers\Index;

use App\Entity\Account;
use App\Entity\FeaturePaymentSet;
use App\Entity\Package;
use App\Entity\Repository\EntityRepository;
use App\Entity\SubscriptionAcquiredType;
use App\Entity\Transaction;
use App\Entity\TransactionPaymentType;
use App\Http\Exceptions\isNotFreemiumSubscriptionException;
use App\Http\Exceptions\FreemiumSubscriptionAlreadySet;
use App\Http\Traits\JsonResponses;
use App\Payment\Exception\DuplicateSubscriptionException;
use App\Payment\Gate2Shop\Gate2ShopTransactionData;
use App\Payment\PayPal\PayPalTransactionData;
use App\Payment\Recurly\TransactionData;
use App\Services\PaymentManager;
use App\Services\PubSub\TransactionalEmailService;
use App\Services\RecurlyService;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use ScholarshipOwl\Data\Entity\Payment\PaymentMethod;
use ScholarshipOwl\Data\Service\Payment\PackageService;
use ScholarshipOwl\Data\Service\Payment\SubscriptionService;

use ScholarshipOwl\Domain\Payment\Gate2Shop\Gate2ShopListener;
use ScholarshipOwl\Domain\Payment\Gate2Shop\MessageFactory;
use ScholarshipOwl\Domain\Payment\Gate2Shop\UrlBuilder;

use ScholarshipOwl\Domain\Payment\PayPal\PayPalListener;
use ScholarshipOwl\Domain\Payment\PayPal\Message as PayPalMessage;

use ScholarshipOwl\Domain\Log\PaymentMessage as LogPaymentMessage;

use ScholarshipOwl\Domain\Payment\QueuePaymentMessage;
use ScholarshipOwl\Domain\Subscription;
use ScholarshipOwl\Util\Mailer;
use ScholarshipOwl\Http\ViewModel;

use Mdb\PayPal\Ipn\Event\MessageVerifiedEvent;
use Mdb\PayPal\Ipn\Event\MessageInvalidEvent;
use Mdb\PayPal\Ipn\Event\MessageVerificationFailureEvent;
use Mdb\PayPal\Ipn\ListenerBuilder\Guzzle\InputStreamListenerBuilder as ListenerBuilder;

use App\Http\Controllers\PaymentController as BasePaymentController;

use Recurly_Account as RecurlyAccount;

/**
 * Payment Controller
 *
 * @author Marko Prelic <markomys@gmail.com>
 */
class PaymentController extends BasePaymentController {
    use JsonResponses;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $packages;

    /**
     * @var PaymentManager
     */
    protected $pm;

    /**
     * PaymentController constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, PaymentManager $pm)
    {
        parent::__construct();
        $this->em = $em;
        $this->pm = $pm;
        $this->packages = $em->getRepository(Package::class);
    }

    /**
	 * Payment Form Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function paymentFormAction() {
		$content = "";

		try {
			$packageService = new PackageService();

			$packageId = $this->getQueryParam("packageId");
			$trackingParams = $this->getQueryParam("trackingParams");

			if(!empty($packageId)) {
				$package = $packageService->getPackage($packageId);
                $account = $this->getLoggedUser();
                $G2SUrlBuilder = new UrlBuilder($package, $account, $trackingParams);

				return $this->redirect($G2SUrlBuilder->getPaymentUrl());
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		echo $content;
	}


    /**
     * Upgrade Mobile Action
     *
     * @access public
     * @return Response
     *
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */
    public function upgradeMobileAction(Request $request)
    {
    	if (!$this->isMobile()) {
    		return $this->redirect("my-account?upgrade=true");
    	}

        $model = $this->getCommonUserViewModel("payment/mobile/upgrade-mobile");
        $model->mobileSpecialOfferOnly = FeaturePaymentSet::config()->getMobileSpecialOfferOnly();

        $this->registerHasOffers('upgrade-mobile');

		if(setting("register.redirect_page_mobile") == "upgrade-mobile"){
			$this->registerHasOffers("select");
		}

        return $model->send();
    }

	/**
	 * Payment Success Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function paymentSuccessAction()
    {
		try {

            $gate2ShopFactory = new MessageFactory();
            $gate2ShopMessage = $gate2ShopFactory->generateMessage($this->getAllInput(), $this->isMobile(), Subscription::SOURCE_WEBSITE);
            $transactionData = new Gate2ShopTransactionData($gate2ShopMessage);

            try {
                \PaymentManager::applyPackageOnAccount(
                    \EntityManager::findById(Account::class, $gate2ShopMessage->getAccount()->getAccountId()),
                    \EntityManager::findById(Package::class, $gate2ShopMessage->getPackage()->getPackageId()),
                    SubscriptionAcquiredType::PURCHASED,
                    $transactionData,
                    $gate2ShopMessage->getPaymentMethod(),
                    $gate2ShopMessage->getExternalSubscriptionId()
                );
            } catch (DuplicateSubscriptionException $e) {}

            $trackingParams = $gate2ShopMessage->getTrackingParams();
			\Session::put("payment_tracking", $trackingParams);
            \Session::flash('bought_package_id', $gate2ShopMessage->getPackage()->getPackageId());

            $transactionEmailService = app(TransactionalEmailService::class);
            $transactionEmailService->sendCommonEmail($gate2ShopMessage->getAccount(), TransactionalEmailService::PACKAGE_PURCHASE, [
                "first_name" => $this->getLoggedUser()->getProfile()->getFirstName(),
                "package_name" => $gate2ShopMessage->getPackage()->getName(),
            ],
            [
                "subject" => "Your " . $gate2ShopMessage->getPackage()->getName() . " Package was a success"
            ]);

            if(\Session::has(self::SESSION_SELECTED_SCHOLARSHIPS) && \Session::get("payment_return") == "apply-selected"){
                $scholarshipsIds = \Session::get(self::SESSION_SELECTED_SCHOLARSHIPS);
                \Session::forget(self::SESSION_SELECTED_SCHOLARSHIPS);
                \session::put("payment_return", "/select");
            }

            $finalUrl = "payment-show-success";
            if (!empty($trackingParams)) {
                $finalUrl .= "?" . http_build_query($trackingParams);
            }

            return $this->redirect($finalUrl);
        } catch (\Exception $e) {
            $this->logError($e);

            return $this->redirect("payment-show-fail?" . http_build_query(array(
                'error' => 'Payment failed, please try later. Feel free contact our support.' .
                    (!\App::environment('production') ? $e->getMessage() : '')
            )));
        }
	}


	/**
	 * Payment Show Success Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function paymentShowSuccessAction() {
		$model = new ViewModel("payment/success");
        $url = \Session::get('payment_return', '/my-account');
        $params = array();

		if (\Session::has("payment_tracking")) {
			$urlParams = \Session::get("payment_tracking");
            $params = is_array($urlParams) ? $urlParams : $params;
            \Session::forget("payment_tracking");
		}

		if (!empty($params)) {
			$url .= "?" . http_build_query($params);
		}

        //  Test if user already had a subscription and bought a new one
        $subscriptionService = new SubscriptionService();
        $model->isAdditional = $subscriptionService->getTotalSubscriptionsCount($this->getLoggedUser()->getAccountId()) > 1;

        if(\Session::has("bought_package_id")){
            $packageId = \Session::get("bought_package_id");
            $packageService = new PackageService();
            $package = $packageService->getPackage($packageId);
        }

		$model->url = $url;
		$model->offerId = $this->getHasOffersId();

		return $model->send();
	}

	/**
	 * Payment Fail Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function paymentFailAction() {
		try {
			$input = $this->getAllInput();
			$this->logInfo($input);

			\Session::forget(self::SESSION_SELECTED_SCHOLARSHIPS);

			$failedReason = "";

			if (array_key_exists("Reason", $input)) {
				$failedReason = $input["Reason"];
			}
			else if (array_key_exists("Error", $input)) {
				$failedReason = $input["Error"];
			}

			$data = array(
				"error" => $failedReason,
				"packageId" => $this->getQueryParam("customField1", ''),
			);

            $this->logError($data);

			$url = "payment-show-fail?" . http_build_query($data);

			//\Response::make()->header("X-Frame-Options", "GOFORIT");
			return $this->redirect($url);
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}
	}


	/**
	 * Payment Show Fail Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function paymentShowFailAction() {
		$model = new ViewModel("payment/fail");

		try {
            \Session::put('error', $this->getQueryParam('error'));
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		return $model->send();
	}


	/**
	 * PayPal Success Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function paypalSuccessAction() {
		try {

            $url = \Session::get('payment_return', '/my-account');
            $input = \Input::all();
            $this->logInfo($input);

            $message = new PayPalMessage($input, $this->isMobile(), Subscription::SOURCE_WEBSITE);

            $package = $message->getPackage();
            $account = $message->getAccount();
			if (empty($package) || empty($account)) {
				return $this->redirect("/");
			}

            try {
                \PaymentManager::applyPackageOnAccount(
                    \EntityManager::findById(Account::class, $account->getAccountId()),
                    \EntityManager::findById(Package::class, $package->getPackageId()),
                    SubscriptionAcquiredType::PURCHASED,
                    $message->getBankTransactionId() ? new PayPalTransactionData($message) : null,
                    $message->getPaymentMethod(),
                    $message->getExternalSubscriptionId() ?: null
                );
            } catch (DuplicateSubscriptionException $e) {}

			// Session Data
            \Session::flash("bought_package_id", $message->getPackage()->getPackageId());
            \Session::put("payment_tracking", $message->getTrackingParams());

			if(\Session::has(self::SESSION_SELECTED_SCHOLARSHIPS) && \Session::get("payment_return") == "apply-selected"){
				$scholarshipsIds = \Session::get(self::SESSION_SELECTED_SCHOLARSHIPS);
				\Session::forget(self::SESSION_SELECTED_SCHOLARSHIPS);
                $url = "/select";
			}

            return $this->redirect($url);
		}
		catch (\Exception $exc) {
			handle_exception($exc);
			return $this->redirect("/");
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function storeIpnNotification()
	{
        LogPaymentMessage::log($this->getAllInput(), PaymentMethod::PAYPAL);

		$listenerBuilder = new ListenerBuilder();
		if (!\App::environment("production")) {
			$listenerBuilder->useSandbox(); // use PayPal sandbox
		}
		$listener = $listenerBuilder->build();
		$listener->onVerified(function (MessageVerifiedEvent $event) {
			$this->logInfo("Valid");
            $this->logInfo($event->getMessage());

            $paypalMessage = new PayPalMessage($event->getMessage(), $this->isMobile(), Subscription::SOURCE_REMOTE);
            $queuePaymentMessage = new QueuePaymentMessage();
            $queuePaymentMessage->push(PayPalListener::class, $paypalMessage);
		});

		$listener->onInvalid(function (MessageInvalidEvent $event) {
			$this->logInfo("Invalid");
			$this->logInfo($event->getMessage());
		});

		$listener->onVerificationFailure(function (MessageVerificationFailureEvent $event) {
			$error = $event->getError();
			$this->logInfo($error);
		});

		$listener->listen();
	}

    /**
     * Gate2Shop DMN Listener
     */
    public function storeDmnNotification()
    {
        LogPaymentMessage::log($this->getAllInput(), PaymentMethod::CREDIT_CARD);

        $GS2MessageFactory = new MessageFactory();
        $G2SMessage = $GS2MessageFactory->generateMessage($this->getAllInput(), $this->isMobile(), Subscription::SOURCE_REMOTE);

        $queuePaymentMessage = new QueuePaymentMessage();
        $queuePaymentMessage->push(Gate2ShopListener::class, $G2SMessage);
    }

    /**
     * @param Request $request
     * @param         $packageId
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws isNotFreemiumSubscriptionException
     */
    public function applyFreemiumPackageAction(Request $request, $packageId)
    {
        /**
         * @var Account $account
         */
        $account = \Auth::user();

        try {

            /** @var Package $package */
            $package = $this->packages->findById($packageId);

            if (!$package->isFreemium()) {
                throw new isNotFreemiumSubscriptionException();
            }

            if ($account->isFreemium()) {
                throw new FreemiumSubscriptionAlreadySet();
            }

            $this->pm->applyPackageOnAccount($account, $package, SubscriptionAcquiredType::FREEBIE);

        } catch (\Exception $e) {
            return $this->jsonExceptionHandle($e);
        }

        return $this->paymentPopupResponse($request, $package, $account);
    }
}

