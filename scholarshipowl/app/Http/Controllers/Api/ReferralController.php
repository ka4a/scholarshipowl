<?php

namespace App\Http\Controllers\Api;

use App\Entity\Account;
use App\Entity\Package;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use App\Services\PubSub\TransactionalEmailService;
use Doctrine\ORM\EntityManager;
use ScholarshipOwl\Data\Entity\Account\ReferralAwardType;
use ScholarshipOwl\Data\Entity\Account\ReferralShare;
use ScholarshipOwl\Data\Entity\Payment\Subscription;
use ScholarshipOwl\Data\Entity\Payment\SubscriptionAcquiredType;
use ScholarshipOwl\Data\Service\Account\ReferralAwardAccountService;
use ScholarshipOwl\Data\Service\Account\ReferralAwardService;
use ScholarshipOwl\Data\Service\Account\ReferralService;
use ScholarshipOwl\Data\Service\Account\ReferralShareService;
use ScholarshipOwl\Data\Service\Payment\StatisticService as PaymentStatisticService;
use ScholarshipOwl\Data\Service\Payment\SubscriptionService;
use ScholarshipOwl\Util\Mailer;


/**
 * Referral Controller
 *
 * @author Marko Prelic <markomys@gmail.com>
 */

class ReferralController extends BaseController
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ScholarshipRepository
     */
    protected $scholarships;

    /**
     * ReferAFriendController constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct();
        $this->em = $em;
        $this->scholarships = $em->getRepository(Scholarship::class);
    }

	/**
	 * Referral Index Action - Gets Account Referrals (GET)
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function indexAction() {
		$model = $this->getOkModel("referrals");
		$data = array();

		try {
			$referralService = new ReferralService();
			$paymentStatisticService = new PaymentStatisticService();

			$referrals = $referralService->getAccountReferrals($this->getLoggedUser()->getAccountId());
			$paid = array();

			if (!empty($referrals)) {
				$paid = $paymentStatisticService->hasPaidSubscriptions(array_keys($referrals));
			}


			foreach ($referrals as $accountId => $referral) {
				$data[$accountId] = array(
					"account_id" => $accountId,
					"first_name" => $referral->first_name,
					"last_name" => $referral->last_name,
					"created_date" => date("m/d/Y", strtotime($referral->created_date)),
					"upgraded" => "No"
				);

				if (array_key_exists($accountId, $paid)) {
					$data[$accountId]["upgraded"] = "Yes";
				}
			}


			$model->setData($data);
		}
		catch (\Exception $exc) {
			$this->handleException($exc);
			$model = $this->getErrorModel(self::ERROR_CODE_SYSTEM_ERROR);
		}

		return $model->send();
	}

	/*
     * Referral Mail Action - Invite friend via Email
     *
     * @access public
     * @return Response
     *
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */
	public function mailAction(){
		$model = $this->getOkModel("referrals/mail");
		$data = array();
		$error = "";

		try {
			$input = $this->getAllInput();

			if (empty($input["friends_emails"])) {
				$error = "Please enter emails !";
			}

			if(empty($error)) {
				$emails = explode(" ", $input["friends_emails"]);
				foreach ($emails as $email) {
					if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $transactionEmailService = app(TransactionalEmailService::class);
                        $transactionEmailService->sendCommonEmail($this->getLoggedUser(), TransactionalEmailService::REFER_FRIEND, [
                            "url" => url("")."?referral=".$this->getLoggedUser()->getReferralCode()."&ch=em",
                            "elig_count" => count($this->scholarships->findEligibleNotAppliedScholarshipsIds($this->getLoggedUser()->getAccountId())),
                        ],
                        [
                            "message_recipient" => $email,
                            "message_subject" => "Apply to scholarships worth $203,550 in minutes",
                            "message_from_email" => $this->getLoggedUser()->getEmail(),
                            "message_from_name" => $this->getLoggedUser()->getProfile()->getFullName()
                        ]);
					}else{
						$error = "Invalid Email format !";
					}
				}
				$data = "Your invitation has been sent.";
				$model->setData($data);
			}else{
				$model = $this->getErrorModel(self::ERROR_CODE_EMAIL_INVALID, $error);
			}

		}
		catch (\Exception $exc) {
			$this->handleException($exc);
			$model = $this->getErrorModel(self::ERROR_CODE_SYSTEM_ERROR);
		}

		return $model->send();
	}

	/*
     * Referral Share Action - Save Shares
     *
     * @access public
     * @return Response
     *
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */
	public function shareAction(){
		$model = $this->getOkModel("referrals/share");
		$data = array();
		$error = "";

		try {
			$input = $this->getAllInput();

			if (empty($input["channel"])) {
				$error = "Channel must be set!";
			}

			if(empty($error)) {
				$data = $this->addReferralShare($input["channel"]);
				$model->setData($data);
			}else {
				$model = $this->getErrorModel(self::ERROR_CODE_NO_SHARE_CHANNEL, $error);
			}
		}
		catch (\Exception $exc) {
			$this->handleException($exc);
			$model = $this->getErrorModel(self::ERROR_CODE_SYSTEM_ERROR);
		}

		return $model->send();
	}

	private function addReferralShare($channel){
		try {
			$referralShareService = new ReferralShareService();
			$referralAwardService = new ReferralAwardService();
			$referralAwardAccountService = new ReferralAwardAccountService();
			$subscriptionService = new SubscriptionService();
			$referralShare = new ReferralShare();

			$referralShare->setReferralChannel($channel);
			$referralShare->setAccount($this->getLoggedUser());
			$referralShare->setReferralDate(date("Y-m-d H:i:s"));

			$referralShareService->addReferralShare($referralShare);

			//	Award the reward for sharing
			$awards = $referralAwardService->getReferralAwards(true, true);

			$accountId = $this->getLoggedUser()->getAccountId();
			if (count($awards)) {
				foreach ($awards as $award) {
					$updateAccount = true;
					if ($award->getReferralAwardType()->getReferralAwardTypeId() == ReferralAwardType::NUMBER_OF_SHARES) {
						$conditionsResultSet = \DB::select("SELECT
								share_number, referral_channel
							FROM
								referral_award_share ra
							WHERE
								referral_award_id = ? AND share_number > 0;", array($award->getReferralAwardId()));

						if(count($conditionsResultSet)) {
							foreach ($conditionsResultSet as $condition) {
								$sql = sprintf("SELECT
									rs.account_id
								FROM
									referral_share rs
								WHERE rs.account_id NOT IN (SELECT account_id FROM referral_award_account WHERE award_type = '%s' AND referral_award_id = ?)
								AND rs.referral_channel = ?
								AND rs.account_id = ?
								GROUP BY rs.account_id
								HAVING COUNT(*) >= ?;", \ScholarshipOwl\Data\Entity\Account\ReferralAwardAccount::REFERRED_AWARD);

								$resultSet = \DB::select($sql, array($award->getReferralAwardId(), $condition->referral_channel, $accountId, $condition->share_number));
								if (count($resultSet)) {
									if ($updateAccount) {
										$referralAwardAccountService->saveReferralAwardAccount($accountId, $award->getReferralAwardId(), \ScholarshipOwl\Data\Entity\Account\ReferralAwardAccount::REFERRED_AWARD);
                                        \PaymentManager::applyPackageOnAccount(
                                            \EntityManager::findById(Account::class, $accountId),
                                            \EntityManager::findById(Package::class, $award->getReferredPackage()->getPackageId()),
                                            \App\Entity\SubscriptionAcquiredType::REFERRED
                                        );
										return array(
											"upgraded" => "Yes",
											"successMessage" => $award->getReferredPackage()->getDisplaySuccessMessage()
										);
									}
								}
							}
						}
					}
				}
				return true;
			}
		}catch(Exception $ex){
			return array("upgraded" => "No");
		}
		return array("upgraded" => "No");
	}

}
