<?php
#################################
#                               #
#       ApplyMe Route File      #
#    Developed By Ilya & Vadim  #
#             2016              #
#                               #
#################################


Route::group(['prefix' => 'apply-me/api', 'as' => 'apply-me-api::', 'middleware' => 'domain:2'], function() {
    Route::group(['prefix' => 'v2', 'as' => 'v2.'], function() {

        /* Public routes */
        Route::group(['prefix' => 'auth', 'as' => 'auth'], function () {
            Route::post('/', ['uses' => 'Rest\AuthRestController@authenticate']);
            Route::post('/facebook', [
                'uses' => 'Rest\AuthRestController@authenticateFacebook', 'as' => '.facebook'
            ]);
        });

        Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
            Route::post('/', ['uses' => 'ApplyMe\AccountRestController@store', 'as' => 'store']);
            Route::post('/password/reset', [
                'uses' => 'ApplyMe\AccountRestController@resetPassword', 'as' => 'password.reset'
            ]);
        });

        /* Private routes */
        Route::group(['middleware' => 'auth:web,admin,api'], function () {
            /* NOTIFICATIONS  */
            Route::post('/notification', ['uses' => 'ApplyMe\NotificationController@index', 'as' => 'notification']);
            /* PUSH */
            Route::get('/push', ['uses' => 'ApplyMe\NotificationController@showNotification', 'as' => 'push.index']);

			/* ACCOUNT */
            Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
                Route::get('/{id}', ['uses' => 'ApplyMe\AccountRestController@show', 'as' => 'show']);
                Route::put('/{id}', ['uses' => 'ApplyMe\AccountRestController@update', 'as' => 'update']);
                Route::get('/register/data', [
                    'uses' => 'ApplyMe\AccountRestController@getRegisterData', 'as' => 'register.data'
                ]);
            });

            Route::group(['prefix' => 'scholarship', 'as' => 'scholarship.'], function () {
                Route::match(['get', 'post'], '/eligible', ['uses' => 'ApplyMe\Versions\v2\ScholarshipRestController@eligible', 'as' => 'eligible']);
            });

            /* APPLICATION */
            Route::group(['prefix' => 'application', 'as' => 'application.'], function () {
                Route::get('/', ['uses' => 'Rest\ApplicationRestController@index', 'as' => 'index']);
                Route::post('/', ['uses' => 'ApplyMe\ApplicationController@store', 'as' => 'store']);
                Route::get('/{id}/{accountId?}', [
                    'uses' => 'Rest\ApplicationRestController@showApplication', 'as' => 'show'
                ]);

                /* APPLICATION.ESSAY */
                Route::group(['prefix' => 'essay', 'as' => 'essay.'], function () {
                    Route::post('/', ['uses' => 'Rest\ApplicationTextRestController@store', 'as' => 'store']);
                    Route::get('/{id}', ['uses' => 'Rest\ApplicationTextRestController@show', 'as' => 'show']);
                    Route::put('/{id}', ['uses' => 'Rest\ApplicationTextRestController@update', 'as' => 'update']);
                    Route::delete('/{id}', ['uses' => 'Rest\ApplicationTextRestController@destroy', 'as' => 'destroy']);

                });

                /* APPLICATION.FILE */
                Route::group(['prefix' => 'file', 'as' => 'file.'], function() {
                    Route::post('/',       ['uses' => 'Rest\ApplicationFileRestController@store',   'as' => 'store']);
                    Route::get('/{id}',    ['uses' => 'Rest\ApplicationFileRestController@show',    'as' => 'show']);
                    Route::put('/{id}',    ['uses' => 'Rest\ApplicationFileRestController@update',  'as' => 'update']);
                    Route::delete('/{id}', ['uses' => 'Rest\ApplicationFileRestController@destroy', 'as' => 'destroy']);
                });

                /* APPLICATION.IMAGE */
                Route::group(['prefix' => 'image', 'as' => 'image.'], function() {
                    Route::post('/',       ['uses' => 'Rest\ApplicationImageRestController@store',   'as' => 'store']);
                    Route::get('/{id}',    ['uses' => 'Rest\ApplicationImageRestController@show',    'as' => 'show']);
                    Route::put('/{id}',    ['uses' => 'Rest\ApplicationImageRestController@update',  'as' => 'update']);
                    Route::delete('/{id}', ['uses' => 'Rest\ApplicationImageRestController@destroy', 'as' => 'destroy']);
                });

            });

            /* MAILBOX */
            Route::group(['prefix' => 'mailbox', 'as' => 'mailbox.'], function () {
                Route::get('/', ['uses' => 'Rest\MailboxRestController@index', 'as' => 'index']);
                Route::put('/{id}', ['uses' => 'Rest\MailboxRestController@update', 'as' => 'update']);
            });

            Route::group(['prefix' => 'create', 'as' => 'create.'], function() {
                Route::post('/social', ['uses' => 'Rest\AuthRestController@createSocialAccount', 'as' => 'social']);
                Route::post('/payment', ['uses' => 'ApplyMe\PaymentController@create', 'as' => 'payment']);
            });

            /* SETTINGS */
            Route::get('/settings', ['uses' => 'ApplyMe\SettingsController@index', 'as' => 'settings.index']);
        });
    });

    /*========================*/

    Route::group(['prefix' => 'v1', 'as' => 'v1.'], function () {
        /* Public Routes */
        Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
            Route::post('/', ['uses' => 'ApplyMe\AccountRestController@store', 'as' => 'store']);
            Route::post('/password/reset', [
                'uses' => 'ApplyMe\AccountRestController@resetPassword', 'as' => 'password.reset'
            ]);
        });

        /* Non public routes */
        Route::group(['middleware' => 'auth:web,admin,api'], function() {

            /* NOTIFICATIONS  */
            Route::post('/notification', ['uses' => 'ApplyMe\NotificationController@index', 'as' => 'notification']);

            /* OLD */
            Route::post('/notify', ['uses' => 'ApplyMe\Versions\v1\NotificationController@index', 'as' => 'notify']);

            /* ACCOUNT */
            Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
                Route::get('/{id}', ['uses' => 'ApplyMe\AccountRestController@show', 'as' => 'show']);
                Route::put('/{id}', ['uses' => 'ApplyMe\AccountRestController@update', 'as' => 'update']);
                Route::get('/register/data', [
                    'uses' => 'ApplyMe\AccountRestController@getRegisterData', 'as' => 'register.data'
                ]);
            });

            /* APPLICATION */
            Route::group(['prefix' => 'application', 'as' => 'application.'], function () {
                Route::get('/', ['uses' => 'Rest\ApplicationRestController@index', 'as' => 'index']);
                Route::post('/', ['uses' => 'ApplyMe\ApplicationController@store', 'as' => 'store']);
                Route::get('/{id}/{accountId?}', [
                    'uses' => 'Rest\ApplicationRestController@showApplication', 'as' => 'show'
                ]);

                /* APPLICATION.ESSAY */
                Route::group(['prefix' => 'essay', 'as' => 'essay.'], function () {
                    Route::post('/', ['uses' => 'Rest\ApplicationTextRestController@store', 'as' => 'store']);
                    Route::get('/{id}', ['uses' => 'Rest\ApplicationTextRestController@show', 'as' => 'show']);
                    Route::put('/{id}', ['uses' => 'Rest\ApplicationTextRestController@update', 'as' => 'update']);
                    Route::delete('/{id}', ['uses' => 'Rest\ApplicationTextRestController@destroy', 'as' => 'destroy']);

                });

                /* APPLICATION.FILE */
                Route::group(['prefix' => 'file', 'as' => 'file.'], function() {
                    Route::post('/',       ['uses' => 'Rest\ApplicationFileRestController@store',   'as' => 'store']);
                    Route::get('/{id}',    ['uses' => 'Rest\ApplicationFileRestController@show',    'as' => 'show']);
                    Route::put('/{id}',    ['uses' => 'Rest\ApplicationFileRestController@update',  'as' => 'update']);
                    Route::delete('/{id}', ['uses' => 'Rest\ApplicationFileRestController@destroy', 'as' => 'destroy']);
                });

                /* APPLICATION.IMAGE */
                Route::group(['prefix' => 'image', 'as' => 'image.'], function() {
                    Route::post('/',       ['uses' => 'Rest\ApplicationImageRestController@store',   'as' => 'store']);
                    Route::get('/{id}',    ['uses' => 'Rest\ApplicationImageRestController@show',    'as' => 'show']);
                    Route::put('/{id}',    ['uses' => 'Rest\ApplicationImageRestController@update',  'as' => 'update']);
                    Route::delete('/{id}', ['uses' => 'Rest\ApplicationImageRestController@destroy', 'as' => 'destroy']);
                });

            });

            /* SCHOLARSHIP */
            Route::group(['prefix' => 'scholarship', 'as' => 'scholarship.'], function () {
                Route::get('/eligible', ['uses' => 'ApplyMe\Versions\v1\ScholarshipRestController@eligible','as' => 'eligible']);
            });

            /* MAILBOX */
            Route::group(['prefix' => 'mailbox', 'as' => 'mailbox.'], function () {
                Route::get('/', ['uses' => 'Rest\MailboxRestController@index', 'as' => 'index']);
                Route::put('/{id}', ['uses' => 'Rest\MailboxRestController@update', 'as' => 'update']);
            });

            /* PUSH */
            Route::get('/push', ['uses' => 'ApplyMe\Versions\v1\NotificationController@showNotification', 'as' => 'push.index']);
        });
    });
});
