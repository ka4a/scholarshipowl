<?php
/**
 * Rest controllers
 */
Route::group(['prefix' => 'rest', 'as' => 'rest::', 'namespace' => 'Rest'], function() {
    Route::group(['prefix' => 'v1', 'as' => 'v1.'], function () {
        /* OpenAPI/swagger API documentation */
        Route::group(['middleware' => 'auth:admin'], function() {
            Route::get('/doc', ['uses' => 'OpenAPIController@index', 'as' => 'doc']);
            Route::get('/doc/file', ['uses' => 'OpenAPIController@getFileContent', 'as' => 'doc.file']);
            Route::get('/doc/{fileName}', ['uses' => 'OpenAPIController@getIncludeFileContent'])->where('fileName', '.*\.(yml|yaml)');
        });

        /* Public Routes */
        Route::post('/account', ['uses' => 'AccountRestController@store', 'as' => 'account.store']);
        Route::post('/account/register', ['uses' => 'AccountRestController@register', 'as' => 'account.register']);
        Route::post('/account/register/email', ['uses' => 'AccountRestController@checkRegistrationEmail',
            'as' => 'register.email']);

        Route::post('/auth', ['uses' => 'AuthRestController@authenticate', 'as' => 'auth']);
        Route::post('/auth/session', ['uses' => 'AuthRestController@session', 'as' => 'auth'])->middleware('api');
        Route::post('/auth/facebook', ['uses' => 'AuthRestController@authenticateFacebook', 'as' => 'auth.facebook']);
        Route::get('/auth/magic-link/{token}', ['uses' => 'AuthRestController@authenticateByMagicLink', 'as' => 'auth.magicLink']);

        Route::get('/callback-facebook', ['uses' => 'AccountRestController@callbackFacebook', 'as' => 'callbackFacebook']);

        Route::get('application/count',  ['uses' => 'ApplicationRestController@count',  'as' => 'application.count']);

        Route::get('coregs/{path}/{accountId?}',  ['uses' => 'CoregsRestController@coregs',  'as' => 'coregs']);

        Route::get('fset',  ['uses' => 'FeatureSetRestController@getData',  'as' => 'fset'])->middleware('fset');

        Route::get('/payment_set/plans-page',  ['uses' => 'PaymentFeatureSetRestController@getData',  'as' => 'payment-fset']);

        Route::any('eligibility/base', 'ScholarshipRestController@eligibleBase')
            ->middleware(['cors.allow.origin:*'])
            ->name('eligibility.base');

        Route::get('/options/{accountId?}', ['uses' => 'OptionsRestController@index'])->name('options');

        Route::get('/popup/{pageUrl}/{accountId?}', ['uses' => 'PopupRestController@display'])->name('popup.display');

        Route::post('/contact-form/{location}', ['uses' => 'ContactFormRestController@postContactAction'])->name('rest-post-contact');

        /* WINNER */
        Route::group(['prefix' => 'winner', 'as' => 'winner.'], function () {
            Route::get('/', ['uses' => 'WinnerRestController@index', 'as' => 'index']);
            Route::get('/{winnerId}', ['uses' => 'WinnerRestController@showWinner', 'as' => 'show']);
        });

        Route::get('/settings-public', 'SettingsRestController@getPublicSettings')->name('settings-public');

        Route::get('/eligibility-initial', 'EligibilityCacheController@calculateInitialEligibility')->name('eligibility-initial');

        Route::group(['middleware' => 'auth:api,web'], function() {
            Route::get('/settings-private', 'SettingsRestController@getPrivateSettings')->name('settings-private');
        });
        /* Non public routes */
        Route::group(['middleware' => 'auth:web,admin,api'], function() {

            Route::get('/eligibility_cache',  ['uses' => 'EligibilityCacheController@getEligibilityCache',  'as' => 'eligibility_cache.get']);
            Route::put('/eligibility_cache',  ['uses' => 'EligibilityCacheController@updateShownScholarships',  'as' => 'eligibility_cache.put']);

            Route::get('/account-info', 'AccountInfoRestController@getData')->name('account-info');

            Route::group(['prefix' => 'autocomplete', 'as' => 'autocomplete.'], function() {
                Route::get('/highschool/{q}', 'AutocompleteRestController@highschool')->name('highschool');
                Route::get('/college/{q}', 'AutocompleteRestController@college')->name('college');
                Route::get('/state_and_city/{zipCode}', 'AutocompleteRestController@stateAndCity')
                    ->name('autocomplete.state_and_city');
            });

            /* ACCOUNT */
            Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
                Route::get('/', ['uses' => 'AccountRestController@index', 'as' => 'index']);
                Route::get('/{id}', ['uses' => 'AccountRestController@show', 'as' => 'show']);
                Route::put('/{id}', ['uses' => 'AccountRestController@update', 'as' => 'update']);
                Route::delete('/{id}', ['uses' => 'AccountRestController@destroy', 'as' => 'destroy']);
                Route::get('/{accountId?}/options', ['uses' => 'OptionsRestController@index', 'as' => 'options']);

                Route::get('/link-facebook', 'AccountRestController@linkFacebookAccount')->name('linkFacebook');
                Route::delete('/unlink-facebook', 'AccountRestController@unlinkFacebookAccount')->name('unlinkFacebook');

                Route::get('/register/data', ['uses' => 'AccountRestController@getRegisterData',
                    'as' => 'register.data']);

                /* ACCOUNT.PROFILE */
                Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
                    Route::get('/{id}', ['uses' => 'ProfileRestController@show', 'as' => 'show']);
                    Route::put('/{id}', ['uses' => 'ProfileRestController@update', 'as' => 'update']);
                });

                /* ACCOUNT.FILE */
                Route::group(['prefix' => 'file', 'as' => 'file.'], function () {
                    Route::get('/', ['uses' => 'AccountFileRestController@index', 'as' => 'index']);
                    Route::post('/', ['uses' => 'AccountFileRestController@store', 'as' => 'store']);
                    Route::get('/{id}', ['uses' => 'AccountFileRestController@show', 'as' => 'show']);
                    Route::put('/{id}', ['uses' => 'AccountFileRestController@update', 'as' => 'update']);
                    Route::delete('/{id}', ['uses' => 'AccountFileRestController@destroy', 'as' => 'destroy']);
                });
            });

            Route::group(['prefix' => 'notification', 'as' => 'notification.'], function() {
                Route::group(['prefix' => 'email', 'as' => 'email.'], function() {
                    Route::get('/send/{package_id}', ['uses' => 'EmailNotificationController@sendEmail'])->name('send');
                });
            });

            Route::group(['prefix' => 'application', 'as' => 'application.'], function() {
                // Route::get('/',         ['uses' => 'Rest\ApplicationRestController@index',    'as' => 'index']);
                    Route::post('/',       ['uses' => 'ApplicationRestController@store',    'as' => 'store']);
                    Route::get('/{id}',    ['uses' => 'ApplicationRestController@show',     'as' => 'show']);
                // Route::put('/{id}',     ['uses' => 'Rest\ApplicationRestController@update',   'as' => 'update']);
                    Route::delete('/{id}',  ['uses' => 'ApplicationRestController@destroy',  'as' => 'destroy']);

                Route::group(['prefix' => 'file', 'as' => 'file.'], function() {
                    Route::get('/',        ['uses' => 'ApplicationFileRestController@index',   'as' => 'index']);
                    Route::post('/',       ['uses' => 'ApplicationFileRestController@store',   'as' => 'store']);
                    Route::get('/{id}',    ['uses' => 'ApplicationFileRestController@show',    'as' => 'show']);
                    Route::put('/{id}',    ['uses' => 'ApplicationFileRestController@update',  'as' => 'update']);
                    Route::delete('/{id}', ['uses' => 'ApplicationFileRestController@destroy', 'as' => 'destroy']);
                });

                Route::group(['prefix' => 'special-eligibility', 'as' => 'special-eligibility.'], function() {
                    Route::post('/',       ['uses' => 'ApplicationSpecialEligibilityRestController@store',   'as' => 'store']);
                    Route::get('/{id}',    ['uses' => 'ApplicationSpecialEligibilityRestController@show',    'as' => 'show']);
                    Route::delete('/{id}', ['uses' => 'ApplicationSpecialEligibilityRestController@destroy', 'as' => 'destroy']);
                });

                Route::group(['prefix' => 'image', 'as' => 'image.'], function() {
                    Route::get('/',        ['uses' => 'ApplicationImageRestController@index',   'as' => 'index']);
                    Route::post('/',       ['uses' => 'ApplicationImageRestController@store',   'as' => 'store']);
                    Route::get('/{id}',    ['uses' => 'ApplicationImageRestController@show',    'as' => 'show']);
                    Route::put('/{id}',    ['uses' => 'ApplicationImageRestController@update',  'as' => 'update']);
                    Route::delete('/{id}', ['uses' => 'ApplicationImageRestController@destroy', 'as' => 'destroy']);
                });

                Route::group(['prefix' => 'text', 'as' => 'text.'], function() {
                    Route::get('/',        ['uses' => 'ApplicationTextRestController@index',   'as' => 'index']);
                    Route::post('/',       ['uses' => 'ApplicationTextRestController@store',   'as' => 'store']);
                    Route::get('/{id}',    ['uses' => 'ApplicationTextRestController@show',    'as' => 'show']);
                    Route::put('/{id}',    ['uses' => 'ApplicationTextRestController@update',  'as' => 'update']);
                    Route::delete('/{id}', ['uses' => 'ApplicationTextRestController@destroy', 'as' => 'destroy']);
                });

                Route::group(['prefix' => 'input', 'as' => 'input.'], function() {
                    Route::get('/',        ['uses' => 'ApplicationInputRestController@index',   'as' => 'index']);
                    Route::post('/',       ['uses' => 'ApplicationInputRestController@store',   'as' => 'store']);
                    Route::get('/{id}',    ['uses' => 'ApplicationInputRestController@show',    'as' => 'show']);
                    Route::put('/{id}',    ['uses' => 'ApplicationInputRestController@update',  'as' => 'update']);
                    Route::delete('/{id}', ['uses' => 'ApplicationInputRestController@destroy', 'as' => 'destroy']);
                });

                Route::group(['prefix' => 'survey', 'as' => 'survey.'], function() {
                    Route::post('/',       ['uses' => 'ApplicationSurveyRestController@store',   'as' => 'store']);
                    Route::get('/{id}',    ['uses' => 'ApplicationSurveyRestController@show',    'as' => 'show']);
                    Route::delete('/{id}', ['uses' => 'ApplicationSurveyRestController@destroy', 'as' => 'destroy']);
                });
            });

            Route::group(['prefix' => 'subscription', 'as' => 'subscription.', 'permission' => ['subscriptions' => 'Index']], function() {
                Route::get('/',        ['uses' => 'SubscriptionRestController@index',   'as' => 'index']);
                Route::get('/export',  ['uses' => 'SubscriptionRestController@export',  'as' => 'export']);
                Route::get('/{id}',    ['uses' => 'SubscriptionRestController@show',    'as' => 'show']);
                Route::put('/cancel/{id}', ['uses' => 'SubscriptionRestController@cancelSubscription', 'as' => 'cancel']);
            });

            Route::group(['prefix' => 'onesignal', 'as' => 'onesignal.'], function() {
                Route::group(['prefix' => 'account', 'as' => 'account.'], function() {
                    Route::post('/', ['uses' => 'OneSignalAccountRestController@create'])->name('create');
                });
            });

            /* SCHOLARSHIP */
            Route::group(['prefix' => 'scholarship', 'as' => 'scholarship.'], function () {
                Route::get('/', ['uses' => 'ScholarshipRestController@index', 'as' => 'index']);
                // Route::post('/',        ['uses' => 'Rest\ScholarshipRestController@store',    'as' => 'store']);
                Route::get('/{id}', ['uses' => 'ScholarshipRestController@show', 'as' => 'show']);
                // Route::put('/{id}',     ['uses' => 'Rest\ScholarshipRestController@update',   'as' => 'update']);
                // Route::delete('/{id}', ['uses' => 'ScholarshipRestController@destroy', 'as' => 'destroy']);
                Route::get('/eligible', ['uses' => 'ScholarshipRestController@eligible', 'as' => 'eligible']);

                Route::any('/favorite/{id}', ['uses' => 'ScholarshipRestController@makeFavoriteAction', 'as' => 'favorite']);
                Route::any('/unfavorite/{id}', ['uses' => 'ScholarshipRestController@makeUnfavoriteAction', 'as' => 'unfavorite']);
                Route::get('/sent', ['uses' => 'ScholarshipRestController@sentApplication', 'as' => 'sent']);
            });

            /* MAILBOX */
            Route::group(['prefix' => 'mailbox', 'as' => 'mailbox.'], function () {
                Route::get('/', ['uses' => 'MailboxRestController@index', 'as' => 'index']);
                Route::get('/{id}', ['uses' => 'MailboxRestController@show', 'as' => 'show']);
                Route::put('/{id}', ['uses' => 'MailboxRestController@update', 'as' => 'update']);
            });


        });
    });
});
