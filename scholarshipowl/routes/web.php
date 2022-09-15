<?php

/**
*  @TODO
*
* 	Controllers List.
*  Upon standards in ActionController pattern, all public methods
*  that represents URLs shoud have suffix "Action" unless them are
*  added via Route::controller
*
*  HomeController (homepage)
*  PageController (For all static pages: faq, help...)
*  RegisterController (For registration / landing pages)
*  UserController (For user pages: my account...)
*  AdminController (For all administration pages / reports...)
*  AjaxController (? Should be refactored for JSON-RPC protocol)
*
*  @author Marko Prelic <markomys@gmail.com>
          */

/*
|--------------------------------------------------------------------------
| Application Routes Laravel 5.0
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// sets cookie to switch between Prod and Canary
// clears cookie if no serverMame passed
Route::any('srv/{serverName}', 'Index\SrvController@setCookie');
Route::any('srv', 'Index\SrvController@clearCookie');

// Affiliate Routes (no filters)
Route::any("affiliate/goal/{goalId}/{accountId?}", "Index\AffiliateController@goalAction")->where("goalId", "[0-9]+");
Route::any("affiliate/goal/{goal}/{accountId?}", "Index\AffiliateController@goalRedirectAction")->where('goal', '[A-Za-z0-9]+');
Route::any("affiliate/{apiKey?}/{accountId?}/{goalId?}", "Index\AffiliateController@affiliateAction");

Route::get("unsubscribe", 'Index\UnsubscribeController@unsubscribeAction')->name('unsubscribe');

//  Get Unsubscribed emails list
Route::any("unsubscribed-list", 'Index\UnsubscribeController@getUnsubscribedEmailsAction');

// magic link resolver
Route::get('/ml/{token}', 'Index\LinkResolverController@resolveMagicLink');
// mobile/web link resolver
Route::get('/resolver', 'Index\LinkResolverController@resolveLink');


//  Scholarship Pages Route
Route::get('scholarships/{id}-{slug}', ['uses' => 'Index\ScholarshipController@scholarshipAction', 'middleware' => ['registration.data']])->name('scholarships.view');
Route::get('scholarships/expired', ['uses' => 'Index\ScholarshipController@scholarshipExpiredAction', 'middleware' => ['registration.data']])->name('scholarships.expired');

//  Sitemap Route
Route::get("sitemap.xml", 'Index\SitemapController@sitemapAction');

Route::group(array("middleware" => ["whitelist"]), function() {

    // Login / Logout Route
    Route::get("logout", "Index\HomeController@logoutAction")->name('logout');
    Route::post("post-login", "Index\HomeController@postLoginAction");

    // Social Login Routes
    Route::get('/fb-redirect', 'Index\SocialAuthController@redirect');
    Route::get('/fb-callback', 'Index\SocialAuthController@callback');
    Route::get('/fb-disconnect', 'Index\SocialAuthController@disconnect');
    Route::get('/fb-connect/{token}', 'Index\SocialAuthController@connect');

    $staticPagesRoutes = [
        "help", "faq", "privacy", "terms", "contact",
        "whoweare", "whatwedo", "additional-services", "about-us",
        "what-people-say-about-scholarshipowl",
        "premium-services",
        "advertise-with-us", "partners", "ebook", "list-your-scholarship",
        "partnerships", "press",
        "logos", "press/release", "finding-a-better-way-to-pay-for-college",
        "awards/scholarship-winners", "promotion-rules"
    ];

    // Static Pages Routes
    foreach ($staticPagesRoutes as $staticPage) {
        Route::get($staticPage, ['uses' => 'Index\HomeController@pageAction', 'middleware' => ['registration.data']])->name($staticPage);
    }

    Route::redirect('/awards/scholarship-winners', '/winners', 301);

    Route::get('jobs', 'Index\HomeController@jobs')->name('jobs');

    Route::get('winners', 'Index\HomeController@winners')->name('winners');

    Route::get("lp/scholarship-eligibility-test", ['uses' => 'Index\RegisterController@landingPageAction', 'middleware' => ['guest', 'registration.data']]);
    Route::get("lp/apply-to-hundreds-of-scholarships", ['uses' => 'Index\RegisterController@landingPageAction', 'middleware' => ['guest', 'registration.data']]);
    Route::get("lp/scholarship-eligibility-test-animation", ['uses' => 'Index\RegisterController@landingPageAction', 'middleware' => ['guest', 'registration.data']]);
    Route::get("lp/apply-to-hundreds-of-scholarships-animation", ['uses' => 'Index\RegisterController@landingPageAction', 'middleware' => ['guest', 'registration.data']]);
    Route::get("lp/get-paid-while-studying", ['uses' => 'Index\RegisterController@landingPageAction', 'middleware' => ['guest', 'registration.data']]);
    Route::get("lp/how-to-get-scholarships", ['uses' => 'Index\RegisterController@landingPageAction', 'middleware' => ['guest', 'registration.data']]);

    /** Vue JS Routes */
    Route::get('/lp/double-your-scholarship', ['uses' => 'Index\HomeController@doubleYourScholarship'])
        ->name('lp.double-your-scholarship');

    Route::get('/lp/facebooksignup', ['uses' => 'Index\HomeController@vueLayout'])
        ->name('lp.facebooksignup');

    //  Conversion Landing Page
    Route::get('lp/{path}', ['uses' => 'Index\HomeController@specialOfferPageAction', 'middleware' => ['registration.data']])->name('special-offer-page');

    Route::any("awards/you-deserve-it-scholarship", ['uses' => "Index\HomeController@youDeserveItAction", 'middleware' => ['registration.data']]);
    Route::any("testUpload", "Index\HomeController@testUpload");

    //  Popup Routes
    Route::get("clicks", "Index\PopupController@clicksAction");
    Route::get("loan", "Index\PopupController@loanAction")->name('loanAction');
    Route::get("goal/{goalId}", "Index\PopupController@redirectAction");


    // Post Contact Route
    Route::post("post-forgot-password", "Index\HomeController@postForgotPasswordAction");


    // Reset Password Routes
    Route::get("reset-password", "Index\UserController@resetPasswordAction")->name('reset-password');
    Route::post("post-reset-password", "Index\UserController@postResetPasswordAction");

    Route::post('post-list-scholarship', 'Api\WebsiteController@postSendToPartnerAction')
    ->name('post-list-scholarship');

    //  Test route for pinboard
    //  Route::get("pinboard", function(){
    //      return View::make("users.pinboard")->with("social", false)->with("credit", 0);
    //  });
});

Route::any('ipn', array('uses' => 'Index\PaymentController@storeIpnNotification', 'as' => 'ipn'));
Route::any('dmn', array('uses' => 'Index\PaymentController@storeDmnNotification', 'as' => 'dmn'));
Route::any('webhook/{id?}', ['uses' => 'Index\BraintreeController@webhookAction',  'as' => 'webhook']);

Route::any('stripe/webhook', ['uses' => 'Index\StripeController@webhook',  'as' => 'stripe.webhook']);

Route::any('recurly/webhook', [
    'uses' => 'Index\RecurlyController@webhook',
    'as' => 'recurly.webhook',
    'middleware' => 'auth.basic.once:r3curly,8Nx25e5nwNDEYHZY',
]);

Route::group(['prefix' => 'digest', 'as' => 'digest.'], function () {
    Route::get(
        '/weekly-scholarship/{secret}',
         ['uses' => 'Index\DigestController@weeklyScholarship', 'as' => 'weekly-scholarship']
    );
    Route::get(
        '/weekly-email/{secret}',
         ['uses' => 'Index\DigestController@weeklyEmail', 'as' => 'weekly-email']
    );
});

// Must Be Logged
Route::group(array("middleware" => ["auth",'whitelist']), function() {

	Route::get('/scholarships/{path?}', 'Index\ScholarshipController@scholarshipsAction')
	->where('path', '(.*)')
	->name('scholarships');

	Route::post('/log-error', 'Index\HomeController@logErrorAction')
	->name('logError');

	// Users Routes
	Route::get("my-account", "Index\UserController@myAccountAction")->name('my-account');
	Route::get("my-account-m", "Index\UserController@myAccountMobileAction")->name('my-account-m');
	Route::get("apply", "Index\UserController@applyAction");
	Route::get("select", "Index\UserController@applyAction")->name('select');
	Route::get("mailbox", "Index\UserController@mailboxAction");
	Route::post("post-basic", "Index\UserController@postBasicAction");
	Route::post("post-education", "Index\UserController@postEducationAction");
	Route::post("post-interests", "Index\UserController@postInterestsAction");
	Route::post("post-account", "Index\UserController@postAccountAction");
	Route::post("post-recurrence", "Index\UserController@postRecurrenceAction");

    Route::get('/mailbox/{id}', 'Index\MailboxController@html');

    Route::get("plans", "Index\UserController@plansPageAction")->name('plans');
	/**
	* Braintree integration controllers
	*/
	Route::post('/braintree', 'Index\BraintreeController@postIndexAction')->name('braintree.index')->middleware(['csrf', 'throttle:6,1']);
	Route::get('/braintree/token', 'Index\BraintreeController@generateToken')->name('braintree.generateToken')->middleware(['throttle:6,1']);

	Route::get('/files/{path}', 'Index\FileController@accountFileShow')
	->name('account-file')
	->where('path', '(.*)');


	Route::post("files/upload", "Index\FileController@uploadAction");
	Route::get("/file/download/{filename}", "Index\FileController@downloadAction")->name('file-download');
	Route::any("files/update/{fileId}", "Index\FileController@updateAction");
	Route::any("files/attach", "Index\FileController@attachFileToEssay");
	Route::any("files/detach", "Index\FileController@detachFileFromEssay");
	Route::any("files/edit/{fileId}", "Index\FileController@editFileName")->name('file-edit');
	Route::any("files/delete/{fileId}", "Index\FileController@deleteAction")->name('file-delete');
	Route::any("files/ajaxupload", "Index\FileController@ajaxUploadAction");


	// Register Routes
	Route::get("secure-upgrade", "Index\RegisterController@registerPaymentAction");
	Route::get("register-finish", "Index\RegisterController@registerFinishAction");
	Route::get("dane", "Index\CoregController@daneMediaAction");
	Route::post("post-dane-media", "Index\CoregController@postDaneMediaAction");
	Route::post("dane-media-programs", "Index\CoregController@getDaneMediaPrograms");
	Route::get("zuusa", "Index\CoregController@zuUsaAction");
	Route::post("post-zuusa", "Index\CoregController@postZuUsaAction");
	Route::post("zuusa-programs", "Index\CoregController@getZuUsaPrograms");
	Route::get("double-positive", "Index\CoregController@doublePositiveAction");
	Route::post("post-double-positive", "Index\CoregController@postDoublePositiveAction");

    Route::post('/recurly', 'Index\RecurlyController@applyPackage')->name('recurly.post');

    Route::post('/stripe', 'Index\StripeController@applyPackage')->name('stripe.post');

        // Payment Routes
	Route::get("payment", "Index\PaymentController@indexAction");
	Route::get("more-services", "Index\PaymentController@indexAction");
	Route::get("payment-form", "Index\PaymentController@paymentFormAction");
	Route::get("payment-show-success", "Index\PaymentController@paymentShowSuccessAction");
	Route::get("payment-show-fail", "Index\PaymentController@paymentShowFailAction");
	Route::get("finish", "Index\PaymentController@paymentFinishAction");
	Route::get("payment-finish", "Index\PaymentController@paymentFinishAction");
	Route::get("our-packages", "Index\PaymentController@ourPackagesAction");
	Route::get("PaySuccess", "Index\PaymentController@paymentSuccessAction");
	Route::get("PaymentSuccess", "Index\PaymentController@paymentSuccessAction");
	Route::get("PayFail", "Index\PaymentController@paymentFailAction");
	Route::get("PaymentFail", "Index\PaymentController@paymentFailAction");
	Route::get("paypal-success", "Index\PaymentController@paypalSuccessAction");
	Route::post("paypal-success", "Index\PaymentController@paypalSuccessAction");
	Route::get("upgrade-mobile", "Index\PaymentController@upgradeMobileAction")->name('upgrade-mobile');

    Route::get("apply-freemium/{packageId}", "Index\PaymentController@applyFreemiumPackageAction")->name('apply.freemium.package');

    // Canceling Membership
    Route::get('cancel-membership/{id}', 'Index\UserController@cancelMeebership')->name('cancel-membership');
    Route::get('cancel-subscription/{id}', 'Index\UserController@cancelSubscription')
        ->name('cancel-subscription');
});

// Must NOT Be Logged
Route::group(array("middleware" => ['whitelist']), function() {

	// Homepage
	Route::get("/", ["uses" => 'Index\HomeController@indexAction', "middleware" => ["registration.data"]])->name('homepage');

    Route::get('/register{step}', ['uses' => 'Index\RegisterController@registerAction'])
        ->where('step', '[1-3]?')
        ->name('register');

    Route::post('register', 'Index\RegisterController@register')
        ->middleware(['registration.data', 'cors.allow.origin:*'])
        ->name('register.post');

	Route::post("post-eligibility", 'Index\RegisterController@postEligibilityAction');
    Route::post("post-register", 'Index\RegisterController@postRegisterAction');
    Route::post("post-register-new", 'Index\RegisterController@register')
        ->middleware(['registration.data', 'cors.allow.origin:*']);

	// Register Via Virtual Machine (45.56.66.24)
    Route::get('register-campaign', [
        'middleware' => ['cors.allow.origin:45.56.66.24|scholarshipwinner2016.com'],
        'uses' => 'Index\RegisterController@landingPageVMAction'
    ]);
});

Route::get('/{page}', 'Index\PageController@page')->name('page');


Route::group(['prefix' => 'pubsub', 'as' => 'pubsub.'], function () {
    Route::group(['prefix' => 'sunrise', 'as' => 'sunrise.'], function () {
        Route::post(
            '/scholarship/{secretKey}',
             ['uses' => 'Index\PubSubSunriseScholarshipEndpointController@manageScholarships', 'as' => 'manageScholarships']
        );
        Route::post(
            '/application/{secretKey}',
             ['uses' => 'Index\PubSubSunriseApplicationEndpointController@actualizeApplication', 'as' => 'actualizeApplication']
        );
    });

    Route::group(['prefix' => 'mailbox', 'as' => 'mailbox.'], function () {
        Route::post(
            '/email-received/{secretKey}',
             ['uses' => 'Index\PubSubMailboxController@triggerInboxEmailEvent', 'as' => 'triggerInboxEmailEvent']
        );
    });
});
