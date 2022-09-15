<?php
/*
 |--------------------------------------------------------------------------
 | ScholarshipOwl Configuration File
 |--------------------------------------------------------------------------
 */
return array(
	"mail" => array(
		"user" => array(
			"register" => array(
				"subject" => "Welcome to ScholarshipOwl",
				"from" => array("ScholarshipOwl@scholarshipowl.com", "ScholarshipOwl Site Registration")
			),

			"apply_free" => array(
				"subject" => "First Applications Sent !",
				"from" => array("ScholarshipOwl@scholarshipowl.com", "ScholarshipOwl Site Applications")
			),

			"apply_paid" => array(
				"subject" => "Paid Application Success !",
				"from" => array("ScholarshipOwl@scholarshipowl.com", "ScholarshipOwl Site Applications")
			),

			"forgot_password" => array(
				"subject" => "Forgotten Password !",
				"from" => array("ScholarshipOwl@scholarshipowl.com", "ScholarshipOwl Site Account")
			),

            "change_password" => array(
                "subject" => "Password Changed",
                "from" => array("ScholarshipOwl@scholarshipowl.com", "ScholarshipOwl Site Account")
            ),

			"account_update" => array(
				"subject" => "Account Changes Saved",
				"from" => array("ScholarshipOwl@scholarshipowl.com", "ScholarshipOwl Site Account")
			),

			"package_exhausted" => array(
				"subject" => "Refill Your Subscription",
				"from" => array("ScholarshipOwl@scholarshipowl.com", "ScholarshipOwl Site Package Exhausted"),
			),

			"package_purchase" => array(
				"from" => array("ScholarshipOwl@scholarshipowl.com", "ScholarshipOwl Site Package Purchase")
			),

			"abandoned_application_process" => array(
				"subject" => "Complete Your Applications",
				"from" => array("ScholarshipOwl@scholarshipowl.com", "ScholarshipOwl Site Applications")
			),

			"mailbox_welcome" => array(
				"subject" => "Welcome to your ScholarshipOwl application inbox",
				"from" => array("Owl@ScholarshipOwl.com", "ScholarshipOwl Mailbox"),
			),

            "refer_friend" => array(
                "subject" => "Your friend invited you to ScholarshipOwl",
                "from" => array("ScholarshipOwl@scholarshipowl.com", "ScholarshipOwl Site Account")
            ),

			"application" => array(),
		),
		"system" => array(
			"contact" => array(
				"subject" => "ScholarshipOwl - Contact",
				"from" => array("contact-form@scholarshipowl.com", "ScholarshipOwl Contact Information"),
				"to" => array("contact@scholarshipowl.com")
			),

			"exception" => array(
				"subject" => "ScholarshipOwl - Exception",
				"from" => array("dev@scholarshipowl.net", "ScholarshipOwl Dev"),
				"to" => array()
			),

			"subscriptions_expire" => array(
				"subject" => "ScholarshipOwl - Expired Subscriptions",
				"from" => array("dev@scholarshipowl.net", "ScholarshipOwl Dev"),
				"to" => array("mprelic@scholarshipowl.com", "daniel.better@scholarshipowl.com")
			),

			"subscriptions_renew" => array(
				"subject" => "ScholarshipOwl - Renewed Subscriptions",
				"from" => array("dev@scholarshipowl.net", "ScholarshipOwl Dev"),
				"to" => array("mprelic@scholarshipowl.com", "daniel.better@scholarshipowl.com")
			),

            "referral_award" => array(
                "subject" => "ScholarshipOwl - Referrals Awarded",
                "from" => array("dev@scholarshipowl.net", "ScholarshipOwl Dev"),
                "to" => ['jelenas@scholarshipowl.com']
            ),

			"scholarships_expire" => array(
				"subject" => "ScholarshipOwl - Expired Scholarships",
				"from" => array("dev@scholarshipowl.net", "ScholarshipOwl Dev"),
				"to" => array("mprelic@scholarshipowl.com", "daniel.better@scholarshipowl.com")
			),

			"register" => array(
				"subject" => "ScholarshipOwl - New Registrant",
				"from" => array("registration@scholarshipowl.net", "ScholarshipOwl Site Registration"),
				"to" => array("daniel.better@scholarshipowl.com")
			),

            'list_your_scholarship' => [
                'subject' => 'ScholarshipOwl - I want to list my scholarship in your DB',
                'from'    => ['dev@scholarshipowl.com'],
                'to'      => ['scholarship@scholarshipowl.com'],
            ],

            'loop_report' => [
                'to'      => array('danielb@scholarshipowl.com')
            ],

            'loop_report_payment' => [
                'to'      => array('danielb@scholarshipowl.com', 'maja@scholarshipowl.com')
            ],

            'recurrent_scholarship_notify' => [
                'to'      => ['maja@scholarshipowl.com', 'jelenas@scholarshipowl.com'],
                'from'    => ['dev@scholarshipowl.com', 'System Email'],
                'subject' => 'Scholarships where recurred',
            ],
            'sales_team-notification' => [
                'headers' => [
                    'reply-to'
                ]
            ]
		),
        "mandrill" => array(
            "use_mandrill" => false,  // true to use Mandrill
            "api_key" => "V7rcDcfkxKjrPVsvQ87DLw",  // API key from Mandrill settings
            "template_name" => "base", // Name of base template defined in Mandrill
        ),
        "states_upcoming_payment_notifications" => [
            'CT', // 'Connecticut',
            'FL', // 'Florida',
            'GA', // 'Georgia',
            'IL', // 'Illinois',
            'HI', // 'Hawai',
            'NC', // 'North Carolina',
            'CA', //California
            'OR', //Oregon
            'ND', //North Dakota
        ],
        'sales_team' => array(
            'email' => 'elite@scholarshipowl.com'
        )
    ),
	"payment" => array(
		"gate2shop" => array(
            "merchant_id"      => env('APP_GTS_MERCHANT_ID', '8099060342805314528'),
            "merchant_site_id" => env('APP_GTS_SITE_ID',     '123378'),
            "secret_key"       => env('APP_GTS_SECRET_KEY',  'aScKLag22KMmVbWVmBhPQMvxKGxRVlsRMenOpeHs07p3FkJibRYBeZNnrgxGHjCD'),
            "process_url"      => env('APP_GTS_PROCESS_URL', 'https://secure.Gate2Shop.com/ppp/purchase.do'),
		),
		"paypal" => array(
			"business" => env('PAYMENT_PAYPAL_BUSINESS', 'paypal@scholarshipowl.com'),
		),
        'braintree' => array(
            'environment' => env('BRAINTREE_ENV',         'production'),
            'merchant_id' => env('BRAINTREE_MERCHANT_ID', 'z9pqz639cwvvn6pq'),
            'public_key'  => env('BRAINTREE_PUBLIC_KEY',  '6tpbzspfygh7q3yd'),
            'private_key' => env('BRAINTREE_PRIVATE_KEY', '8bf6b5d5567acf41f7c34efa7d65ec9a'),
        ),
	),

	"supercollege" => array(
		"apikey" => "NCQ-241212-AAZZ",
		"siteurl" => "http://scholarship.com/",
		"siteid" => "8"
	),

	"edumax" => array(
		"campaign_url" => "https://web.edumaximizer.com/14588.cmp"
	),

	"hasoffers" => array(
		"url" => "http://scholarship.go2cloud.org/aff_lsr?",
		"url_goal" => "http://scholarship.go2cloud.org/aff_goal?a=lsr&",
		"goals" => array(
			// Lead
			"register" => array(
				"22" => "0",
				"24" => "8",
				"26" => "4",
				"168" => "60",
			),
			// Account
			"select" => array(
				"22" => "12",
				"24" => "0",
				"26" => "2",
                "168" => "62",
			),
			// Sale
			"payment-show-success" => array(
				"22" => "14",
				"24" => "6",
				"26" => "0",
                "168" => "66",
			),
            'free-trial' => [
                '22' => '38',
                '24' => '34',
                '26' => '36',
                "168" => "64",
            ],
			// Mission
			"mission-accomplished" => array(
				"22" => "26",
				"24" => "28",
				"26" => "30",
			),
		),
	),

	"submission" => array(
        "Toluna" => array(
			"url" => "https://us.toluna.com/coreg/panelists",
            "method" => "POST",
            "auth" => array("SourceId" => "50007636"),
            "options" => array(),
        ),
		"Academix" => array(
			"url" => "https://public.axdapi.com/direct/f65416af-bf0b-43bb-8a4b-cccdc66d73bf",
            "method" => "POST",
            "auth" => [],
            "options" => array(),
        ),
		"AcademixAged" => array(
			"url" => "https://public.axdapi.com/direct/02392fff-4b4f-4424-ae75-ea10ac392761",
            "method" => "POST",
            "auth" => [],
            "options" => array(),
        ),
		"LoanStaging" => array(
			"url" => "http://guidetolenders.quinstage.com/personalloans/leadpost.jsp",
			"method" => "GET",
			"auth" => array("AFN" => "scholarshipowl_PL", "AF" => 93603144),
			"options" => array(),
		),
		"LoanProduction" => array(
			"url" => "https://www.guidetolenders.com/personalloans/leadpost.jsp",
			"method" => "GET",
			"auth" => array("AFN" => "scholarshipowl_PL", "AF" => 93603144),
			"options" => array(),
		),
		"DaneMedia" => array(
            "url" => "https://classic.leadconduit.com/v2/PostLeadAction",
			"method" => "POST",
			"auth" => array("xxAccountId" => "058l6g0l1"),
			"options" => array(),
		),
		"Cappex" => array(
            "url" => "https://my.cappex.com/api/account/manage",
            "method" => "POST",
            "auth" => array("key" => "scholarship-owl", "programId" => "5884930"),
            "options" => array()
        ),
		"SimpleTuition" => array(
            "url" => "http://rtapi.simpletuition.com:9000/subscriber/v1",
            "method" => "POST",
            "auth" => array("username" => "owl", "password" => "NA5rI0us9Mo08ER8Jo"),
            "options" => array()
        ),
        "Berecruited" => array(
            "url" => "https://recruit-match.ncsasports.org/api/submit/v1/new_recruit",
            "method" => "POST",
            "auth" => [],
            "options" => array(),
        ),
        "OpinionOutpost" => array(
            "url" => "http://service.surveysampling.com/Intake/PostLead",
            "method" => "GET",
            "auth" => array("offer_id" => "680", "affiliate_id" => "4839", "cpid" => 9795, "jtype" => "d"),
            "options" => array(),
        ),
        "ZuUsa" => array(
            "url" => "https://classic.leadconduit.com/v2/PostLeadAction",
            "method" => "POST",
            "auth" => array("xxAccountId" => "058l6g0l1"),
            "options" => array(),
        ),
        "WayUp" => array(
            "url" => "https://www.wayup.com/webhooks/partner/scholarshipowl/students/",
            "method" => "POST",
            "auth" => array(),
            "options" => array(),
        ),
        "CwlStaging" => array(
            "url" => "https://stage.collegeweeklive.com/service/partner/api/user/refcode/PAR_SCHOLARSHIPOWL_API",
            "method" => "POST",
            "auth" => array(),
            "options" => array(),
        ),
        "CwlProduction" => array(
            "url" => "https://www.collegeweeklive.com/service/partner/api/user/refcode/PAR_SCHOLARSHIPOWL_API",
            "method" => "POST",
            "auth" => array(),
            "options" => array(),
        ),
        "CollegeExpress" => array(
            "url" => "https://www.collegexpress.com/bluebird/offer/10753/form",
            "method" => "POST",
            "auth" => array(),
            "options" => array(),
        ),
        "Ziprecruiter" => array(
            "url" => "https://api.ziprecruiter.com/job-alerts/v2/subscriber",
            "method" => "POST",
            "auth" => array("username" => "k279696mnjcjptamyv5radp5ywh99azq", "password" => ""),
            "options" => array(),
        ),
        "Birddog" => array(
            "url" => "http://bdmleadmanagement.valid1.net/leads.aspx",
            "method" => "POST",
            "auth" => array(),
            "options" => array(),
        ),
        "InboxDollars" => array(
            "url" => "https://partner.inboxdollars.com/coreg",
            "method" => "POST",
            "auth" => array(
                'affiliate_auth' => "7bUbUbn23725097"
            ),
            "options" => array(),
        ),
        "ISay" => array(
            "url" => "https://rec.i-say.com/survey/us/coreg/3/register",
            "method" => "POST",
            "auth" => array(
                'utm_source' => "ScholarshipOwl_US_CPL_CPA_Oct_2018"
            ),
            "options" => array(),
        )
	),

    'collegeRecruiter' => array(
        'host' => '23.253.229.151',
        'port' => 22222,
        'path' => '/files',
        'username'  => 'scholarshipowl',
        'password'  => 'xEpr2nEr7rEt',
    ),

    'partner-access-keys' => array( "p33S4VRWSk3WJ8CU2nPc85Kyqc74uq5P",
									"H0uxMVgg21wbdfp990W1G5G3OpHLWPNI",
                                    "Eiraquoo6ahkow6ohgoog5Daiph1aeco",
                                    "eeKi0eQuoo9nae6aithaK6Coh3ojoohe",
                                    "Peipheshoghoolae0Lai3aiQuieLahB0",
                                    "zeeQuiaquoh1thaeyae8aeroing3yua7",
                                    "ohge6uuHee7hei8eechae8zeimees7te",
                                    "af4sho6Ookuashiegohng0yek5aeth5a",
                                    "AiqueiQuaeCai7kahpeQuiThoofejaib",
                                    "Eigh4eosh4eipai2autahvoh7ooshub8",
                                    "ied8OhpeedohV5Leequiileilai3ahBu",
                                    "ow9okie5shuo4XeicohnuMeighaZaece",
                                    "vaec5pi0zai5ohPhaith7loh4ohph1ui",
                                    "iiGoh9aiv4weir9ju7aeNgaethie3aev",
                                    "eceiSh1OhcahdaeM9Oox0thiu1oophie",
                                    "Wosa1feexuThu5lahqu7Quuanief8jai",
                                    "Huiy2sei0faigeebee0oe5eev0quah2e",
                                    "mashieB5Eej4Phaing3ueCh8shahthoe",
                                    "elahJahghahtaeP1thaiSaiz6non6gei",
                                    "ga3te9oosaifesaiFieFoomai4hohdee",
                                    "oe4that0shooth0thug2JeepaeShah5o",
                                    "Uip7eiK7uel3Aj8qui9roch0thiecohw"),

    'uloop' => array(
        'ftp' => array(
            'host' =>     env('ULOOP_HOST', 'ftp.uloop.com'),
            'login' =>    env('ULOOP_LOGIN', 'scholarshipowl'),
            'password' => env('ULOOP_PASSWORD', 'uLooPScholarShipowl'),
        )
    ),

    'collegeFactual' => array(
        'sftp' => array(
            'host' => '174.37.233.35',
            'port' => 22,
            'path' => '/usr/scholarship_data',
            'username'  => 'scholarshipowl',
            'password'  => 'Dqg3D4q2CzmppmQy',
        ),
    ),
);
