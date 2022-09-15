<?php
// Must Be Logged
Route::group(array("middleware" => ["auth",'whitelist']), function() {
	// API Routes
	Route::group(['prefix' => 'api/v1.0', 'as' => 'api::'], function() {

        Route::group(['prefix' => '/account', 'as' => 'account.'], function() {
            Route::get('/', ['uses' => 'Api\AccountController@accountAction'])->name('index');
        });

        //  API (Apply)
		Route::get("apply", "Api\ApplyController@indexAction");
		Route::post("apply", "Api\ApplyController@postIndexAction");

		//  API (My-Applications)
		Route::get("my-applications", "Api\MyApplicationsController@indexAction");
		Route::put("my-applications", "Api\MyApplicationsController@putIndexAction");
		Route::delete("my-applications", "Api\MyApplicationsController@deleteIndexAction");

		//  API (Subscription)
		Route::get("subscription/current", "Api\SubscriptionController@currentAction");

		//  API (Refer A Friend)
		Route::get("referrals", "Api\ReferralController@indexAction");
		Route::post("referrals/mail", "Api\ReferralController@mailAction");
		Route::post("referrals/share", "Api\ReferralController@shareAction");

        //  API Missions
		Route::get("missions", "Api\MissionsController@indexAction");
		Route::get("missions/history", "Api\MissionsController@historyAction");
		Route::get("missions/status/{missionId}", "Api\MissionsController@statusAction");
		Route::post("missions/refer-a-friend/{referralAwardId}", "Api\MissionsController@startReferAFriendAction");
		Route::post("missions/notify", "Api\MissionsController@notifyAction");

		//  API Popup
		Route::get("popup/{popupId}", "Api\WebsiteController@popupAction");

		//	API Packages
		Route::get("package/view/{popupId}", "Api\PackagesController@getPackageViewAction");
	});
});

