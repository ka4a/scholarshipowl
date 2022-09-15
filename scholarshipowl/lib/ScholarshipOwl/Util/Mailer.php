<?php

/**
 * AbstractService
 *
 * @package     ScholarshipOwl\Util
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	10. December 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Util;


use App\Entity\Domain;
use App\Entity\Profile;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use App\Entity\TransactionalEmail;
use App\Entity\TransactionalEmailSend;
use App\Jobs\MandrillBulkTemplateJob;
use App\Jobs\MandrillTemplateJob;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\NoResultException;
use ScholarshipOwl\Data\DateHelper;
use ScholarshipOwl\Data\Entity\Account\Account;
use ScholarshipOwl\Data\Service\Account\ProfileService;

class Mailer {
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

    const MANDRILL_SINGLE_SUCCESSFUL_DEPOSIT = "mandrill-successful-non-recurrent-deposit";
    const MANDRILL_INITIAL_SUCCESSFUL_DEPOSIT = "mandrill-1st-successful-deposit";
    const MANDRILL_RECURRENT_SUCCESSFUL_DEPOSIT = "mandrill-successful-repeat-deposit";
    const MANDRILL_SINGLE_FAILED_DEPOSIT = "mandrill-1st-failed-deposit";
    const MANDRILL_INITIAL_FAILED_DEPOSIT = "mandrill-failed-recurrent-deposit";
    const MANDRILL_RECURRENT_FAILED_DEPOSIT = "mandrill-failed-recurrent-deposit";
    const MANDRILL_SINGLE_SUBSCRIPTION_CREATED = "mandrill-membership-awarded-one-time-billing";
    const MANDRILL_INITIAL_SUBSCRIPTION_CREATED = "mandrill-membership-awarded-repeat-billing";
    const MANDRILL_RECURRENT_SUBSCRIPTION_CREATED = "mandrill-membership-renewed-repeat-billing";
    const MANDRILL_SINGLE_SUBSCRIPTION_EXPIRED = "mandrill-membership-expired";
    const MANDRILL_RECURRENT_SUBSCRIPTION_EXPIRED = "mandrill-membership-expired";
    const MANDRILL_APPLICATIONS_SENT = "mandrill-application-s-sent-succesfully";
    const MANDRILL_APPLICATIONS_EXPIRE_48H = "mandrill-application-s-expire-within-48h";
    const MANDRILL_ABANDONED_APPLICATION_PROCESS = "abandoned-application-process";
    const MANDRILL_APPLY_FREE = "apply-free";
    const MANDRILL_APPLY_PAID = "apply-paid";
    const MANDRILL_ACCOUNT_UPDATE = "account-update";
    const MANDRILL_FORGOT_PASSWORD = "forgot-password";
    const MANDRILL_FORGOT_PASSWORD_APPLY_ME = "forgot-password-apply-me";
    const MANDRILL_MAILBOX_WELCOME = "mailbox-welcome";
    const MANDRILL_MAILBOX_WELCOME_APPLY_ME = "mailbox-welcome-apply-me";
    const MANDRILL_PACKAGE_EXHAUSTED = "package-exhausted";
    const MANDRILL_PACKAGE_PURCHASE = "purchase-package";
    const MANDRILL_REGISTER = "register";
    const MANDRILL_CHANGE_PASSWORD = "change-password";
    const MANDRILL_REFER_FRIEND = "refer-a-friend";
    const MANDRILL_ACCOUNT_WELCOME = "account-welcome";
    const MANDRILL_ACCOUNT_WELCOME_APPLY_ME = "account-welcome-apply-me";
    const MANDRILL_YDIT_CONFIRMATION = "you-deserve-it-confirmation";
    const MANDRILL_YDIT_CONFIRMATION_APPLY_ME = "you-deserve-it-confirmation-apply-me";
    const MANDRILL_NEW_EMAIL = "new-email";
    const MANDRILL_NEW_EMAIL_APPLY_ME = "new-email-apply-me";
    const MANDRILL_NEW_ELIGIBLE_SCHOLARSHIPS = "new-eligible-scholarships";
    const MANDRILL_RECURRENT_SCHOLARSHIPS_NOTIFY = "recurrent-scholarships-notify";

    const MANDRILL_FREETRIAL_ACTIVATED = 'free-trial-activated';
    const MANDRILL_FREETRIAL_CANCELLED = 'free-trial-cancelled';
    const MANDRILL_FREETRIAL_FIRST_CHARGE = 'first-charge-from-free-trial';

    const MANDRILL_SUBSCRIPTION_CREDIT_EXHAUSTED = 'subscription-credit-exhausted';
    const MANDRILL_SUBSCRIPTION_CREDIT_INCREASES = 'subscription-credit-increases';

    const MANDRILL_SUBSCRIPTION_UPCOMING = 'subscription-and-payment-renewal';
    const MANDRILL_SUBSCRIPTION_FREETRIAL_EXPIRING = 'subscription-freetrial-expiring';
    const MANDRILL_SUBSCRIPTION_ACTIVATED = 'subscription-activated';

    const SYSTEM_CONTACT = "system.contact";
	const SYSTEM_EXCEPTION = "system.exception";
	const SYSTEM_SCHOLARSHIPS_EXPIRE = "system.scholarships_expire";
	const SYSTEM_SUBSCRIPTIONS_EXPIRE = "system.subscriptions_expire";
	const SYSTEM_SUBSCRIPTIONS_RENEW = "system.subscriptions_renew";
	const SYSTEM_REFERRAL_AWARD = "system.referral_award";
	const SYSTEM_REGISTER = "system.register";

	const SYSTEM_RECURRENT_SCHOLARSHIPS_NOTIFY = "system.recurrent_scholarship_notify";

	const SYSTEM_SALES_TEAM_NOTIFICATION = "system.sales_team-notification";


	public static function send($type, $data = array(), $to = null, $subject = null, $from = null, $attachment = null) {
		if(!\App::environment('production') || !\Domain::isScholarshipOwl()) {
			return false;
		}

		$config = \Config::get("scholarshipowl.mail." . $type);

		if(!empty($config) && empty($to)) {
			$to = @$config["to"];
		}

		if(!empty($config) && empty($subject)) {
			$subject = @$config["subject"];
		}

		if(!empty($config) && empty($from)) {
			$from = @$config["from"];
		}


		if(!is_array($to)) {
			$to = array($to);
		}

		if(empty($to)) {
			throw new \RuntimeException("Mailer: to is empty");
		}

		if(empty($subject)) {
			throw new \RuntimeException("Mailer: subject is empty");
		}

		if(empty($from)) {
			throw new \RuntimeException("Mailer: from is empty");
		}

		$accountEmail = $data['email'];

        if(\Config::get("scholarshipowl.mail.mandrill.use_mandrill")) {
            try {
                $mandrill = new \Mandrill(\Config::get("scholarshipowl.mail.mandrill.api_key"));
                $template_name = \Config::get("scholarshipowl.mail.mandrill.template_name");

                $view = \View::make("emails." . $type, $data);
                $content = $view->render();

                $template_content = array(
                    array(
                        "name" => "main",
                        "content" => $content
                    )
                );
                foreach ($to as $recipient) {
                    $message = array(
                        "html" => $content,
                        "subject" => $subject,
                        "from_email" => $from[0],
                        "from_name" => $from[1],
                        "to" => array(
                            array(
                                "email" => $recipient,
                                "type" => "to"
                            )
                        ),
                        "track_opens" => true,
                        "track_clicks" => true,
                    );

                    if(isset($config['headers']) && $config['headers'][0] == 'reply-to'){
                        $message['headers']['Reply-To'] = $accountEmail;
                    }

                    $mandrill->messages->sendTemplate($template_name, $template_content, $message);
                }
            } catch(\Mandrill_Error $e) {
                \Log::error("A mandrill error occurred: " . get_class($e) . " - " . $e->getMessage());
            }
        }else {
            foreach($to as $recipient) {
                if(!isset($attachment)) {
                    \Mail::queue(
                        array("html" => "emails.$type"),
                        $data,
                        function($message) use ($subject, $from, $recipient, $accountEmail, $config) {
                            $message->from($from[0], $from[1]);
                            $message->subject($subject);
                            $message->to($recipient);
                            if(isset($config['headers']) && $config['headers'][0] == 'reply-to'){
                                $message->setReplyTo($accountEmail);
                            }
                        }
                    );
                }
                else {
                    \Mail::queue(
                        array("html" => "emails.$type"),
                        $data,
                        function($message) use ($subject, $from, $recipient, $attachment) {
                            $message->from($from[0], $from[1]);
                            $message->subject($subject);
                            $message->to($recipient);
                            $message->attach($attachment[0], array("as" => $attachment[1]));
                        }
                    );
                }
            }
        }
	}

    public static function sendMandrillTemplate($template, $accountId, $data = array(), $to = null, $subject = null, $from = null)
    {
        MandrillTemplateJob::dispatch($template, $accountId, $data, $to, $subject, $from);
    }

    public static function sendMandrillBulkTemplate($template, $accountIds, $data = array())
    {
        MandrillBulkTemplateJob::dispatch($template, $accountIds, $data);
    }

    public static function getMandrillConstant($type){
        return constant('self::MANDRILL_'.strtoupper($type));
    }
}
