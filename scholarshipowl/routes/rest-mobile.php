<?php

Route::group(['prefix' => 'rest-mobile', 'as' => 'rest-mobile::', ], function() {
    Route::group(['prefix' => 'v1', 'as' => 'v1.'], function() {

        /* OpenAPI/swagger API documentation */
        Route::group(['middleware' => 'auth:admin'], function() {
            Route::get('/doc', ['uses' => 'RestMobile\OpenAPIController@index', 'as' => 'doc']);
            Route::get('/doc/file', ['uses' => 'RestMobile\OpenAPIController@getFileContent', 'as' => 'doc.file']);
            Route::get('/doc/{fileName}', ['uses' => 'RestMobile\OpenAPIController@getIncludeFileContent'])->where('fileName', '.*\.(yml|yaml)');
        });

        /* Public routes */
        Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
            Route::post('/', ['uses' => 'RestMobile\AuthController@authenticate', 'as' => 'authenticate']);
            Route::post('/facebook', [
                'uses' => 'RestMobile\AuthController@authenticateFacebook', 'as' => 'authenticate.facebook'
            ]);
            Route::post('/one-time-auth-token', ['uses' => 'RestMobile\AuthController@authenticateByOneTimeToken', 'as' => 'authenticateByOneTimeToken']);
            Route::get('/authenticate-and-redirect', ['uses' => 'RestMobile\AuthController@authenticateAndRedirect', 'as' => 'authenticateAndRedirect']);
            Route::post('/magic-link', ['uses' => 'RestMobile\AuthController@authenticateByMagicLink', 'as' => 'magicLink']);
        });

        Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
            Route::post('/', ['uses' => 'RestMobile\AccountController@create', 'as' => 'create']);
            Route::put('/password/reset', [
                'uses' => 'RestMobile\AccountController@passwordReset', 'as' => 'password.reset'
            ]);
        });

        /* WINNER */
        Route::group(['prefix' => 'winner', 'as' => 'winner.'], function () {
            Route::get('/', ['uses' => 'Rest\WinnerRestController@index', 'as' => 'index']);
            Route::get('/{winnerId}', ['uses' => 'Rest\WinnerRestController@showWinner', 'as' => 'show']);
        });

        /* TRANSACTIONAL EMAILS */
        Route::group(['prefix' => '/transactional-emails', 'as' => 'transactional-emails.'], function () {
            Route::get('/app-membership-invite', [
                'uses' => 'RestMobile\TransactionalEmailController@triggerAppMembershipInvite',
                'as' => 'app-invite',
                'middleware' => 'auth:api'
            ]);
            Route::get('/magic-link/{email}', ['uses' => 'RestMobile\TransactionalEmailController@triggerAppMagicLink', 'as' => 'app-magic-link']);
            Route::get('/password-reset/{email}', ['uses' => 'RestMobile\TransactionalEmailController@resetPassword', 'as' => 'password-reset']);
        });

        Route::group(['middleware' => 'auth:api'], function () {
            /* ELIGIBILITY CACHE */
            Route::get('/eligibility_cache',  ['uses' => 'Rest\EligibilityCacheController@getEligibilityCache',  'as' => 'eligibility_cache.get']);
            Route::put('/eligibility_cache',  ['uses' => 'Rest\EligibilityCacheController@updateShownScholarships',  'as' => 'eligibility_cache.put']);

            /* ACCOUNT INFO */
            Route::get('/account-info', 'Rest\AccountInfoRestController@getData')->name('account-info');

            /* ACCOUNT */
            Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
                Route::get('/', ['uses' => 'RestMobile\AccountController@show', 'as' => 'show']);
                Route::put('/', ['uses' => 'RestMobile\AccountController@update', 'as'   => 'update']);
                Route::delete('/', ['uses' => 'RestMobile\AccountController@delete', 'as'   => 'delete']);
                Route::put('/password/change', [
                    'uses' => 'RestMobile\AccountController@passwordChange', 'as' => 'password.change'
                ]);
                Route::get('/form-options', ['uses' => 'RestMobile\AccountController@formOptions','as'  => 'form-options']);
                Route::put('/app-installed', ['uses' => 'RestMobile\AccountController@appInstalled','as'  => 'app-installed']);
                Route::put('/app-uninstalled', ['uses' => 'RestMobile\AccountController@appUninstalled','as'  => 'app-uninstalled']);
            });

            /* SCHOLARSHIP */
            Route::group(['prefix' => 'scholarship', 'as' => 'scholarship.'], function () {
                Route::get('/eligible', ['uses' => 'RestMobile\ScholarshipRestController@eligible', 'as' => 'eligible']);
                Route::put('/favorite/{id}', ['uses' => 'Rest\ScholarshipRestController@makeFavoriteAction', 'as' => 'favorite']);
                Route::put('/unfavorite/{id}', ['uses' => 'Rest\ScholarshipRestController@makeUnfavoriteAction', 'as' => 'unfavorite']);
                Route::get('/sent', ['uses' => 'RestMobile\ScholarshipRestController@sentApplication', 'as' => 'sent']);
            });

            /* APPLICATION */
            Route::group(['prefix' => 'application', 'as' => 'application.'], function () {
                Route::get('/', ['uses' => 'RestMobile\ApplicationController@index', 'as' => 'index']);
                Route::post('/', ['uses' => 'Rest\ApplicationRestController@store', 'as' => 'store']);
                Route::get('/{scholarshipId}', [
                    'uses' => 'RestMobile\ApplicationController@showApplication', 'as' => 'show'
                ]);

                /* APPLICATION.ESSAY */
                Route::group(['prefix' => 'essay', 'as' => 'essay.'], function () {
                    Route::post('/', ['uses' => 'Rest\ApplicationTextRestController@store', 'as' => 'store']);
                    Route::get('/{id}', ['uses' => 'Rest\ApplicationTextRestController@show', 'as' => 'show']);
                    Route::delete('/{id}', ['uses' => 'Rest\ApplicationTextRestController@destroy', 'as' => 'destroy']);
                });

                /* APPLICATION.FILE */
                Route::group(['prefix' => 'file', 'as' => 'file.'], function() {
                    Route::post('/',       ['uses' => 'Rest\ApplicationFileRestController@store',   'as' => 'store']);
                    Route::get('/{id}',    ['uses' => 'Rest\ApplicationFileRestController@show',    'as' => 'show']);
                    Route::delete('/{id}', ['uses' => 'Rest\ApplicationFileRestController@destroy', 'as' => 'destroy']);
                });

                /* APPLICATION.IMAGE */
                Route::group(['prefix' => 'image', 'as' => 'image.'], function() {
                    Route::post('/',       ['uses' => 'Rest\ApplicationImageRestController@store',   'as' => 'store']);
                    Route::get('/{id}',    ['uses' => 'Rest\ApplicationImageRestController@show',    'as' => 'show']);
                    Route::delete('/{id}', ['uses' => 'Rest\ApplicationImageRestController@destroy', 'as' => 'destroy']);
                });

                /* APPLICATION.INPUT */
                Route::group(['prefix' => 'input', 'as' => 'input.'], function() {
                    Route::post('/',       ['uses' => 'Rest\ApplicationInputRestController@store', 'as' => 'store']);
                    Route::get('/{id}',    ['uses' => 'Rest\ApplicationInputRestController@show', 'as' => 'show']);
                    Route::delete('/{id}', ['uses' => 'Rest\ApplicationInputRestController@destroy', 'as' => 'destroy']);
                });

                /* APPLICATION.SPECIAL-ELIGIBILITY */
                Route::group(['prefix' => 'special-eligibility', 'as' => 'special-eligibility.'], function() {
                    Route::post('/',       ['uses' => 'Rest\ApplicationSpecialEligibilityRestController@store',   'as' => 'store']);
                    Route::get('/{id}',    ['uses' => 'Rest\ApplicationSpecialEligibilityRestController@show',    'as' => 'show']);
                    Route::delete('/{id}', ['uses' => 'Rest\ApplicationSpecialEligibilityRestController@destroy', 'as' => 'destroy']);
                });

                /* APPLICATION.SURVEY */
                Route::group(['prefix' => 'survey', 'as' => 'survey.'], function() {
                    Route::post('/',       ['uses' => 'Rest\ApplicationSurveyRestController@store',   'as' => 'store']);
                    Route::get('/{id}',    ['uses' => 'Rest\ApplicationSurveyRestController@show',    'as' => 'show']);
                    Route::delete('/{id}', ['uses' => 'Rest\ApplicationSurveyRestController@destroy', 'as' => 'destroy']);
                });

            });

            /* MAILBOX */
            Route::group(['prefix' => 'mailbox', 'as' => 'mailbox.'], function () {
                Route::get('/', ['uses' => 'Rest\MailboxRestController@index', 'as' => 'index']);
                Route::put('/{id}', ['uses' => 'Rest\MailboxRestController@update', 'as' => 'update']);
            });

            /* FILES */
            Route::group(['prefix' => 'file', 'as' => 'file.'], function () {
                Route::get('/{accountFileId}/show', ['uses' => 'RestMobile\FileController@accountFileShow', 'as' => 'accountFileShow']);
                Route::get('/{accountFileId}/download', ['uses' => 'RestMobile\FileController@accountFileDownload', 'as' => 'accountFileDownload']);
            });
        });
    });
});
