<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/application', 'as' => 'application.'], function () {
    Route::post('', ['uses' => 'Rest\ApplicationController@apply'])->name('create');


    Route::group(['as' => 'related.winner.'], function () {
        /**
         * Use POST for submit because maybe used on old browser not support PATCH method for update.
         */
        Route::post('/{id}/winner', 'Rest\ApplicationController@relatedWinnerUpdate')->name('update');
        Route::get('/{id}/winner', 'Rest\ApplicationController@relatedWinner')->name('show');
    });

});

Route::group(['prefix' => '/scholarship', 'as' => 'scholarship.'], function () {
    Route::post('/', ['uses' => 'Rest\ScholarshipController@eligible'])->name('eligible');

    Route::get('/{id}', ['uses' => 'Rest\ScholarshipController@show'])->name('show');
    Route::post('/{id}/apply', ['uses' => 'Rest\ScholarshipController@apply'])->name('apply');
    Route::post('/apply', ['uses' => 'Rest\ScholarshipController@applyBatch'])->name('applyBatch');
});

Route::post('preview.pdf', 'Rest\ScholarshipTemplateContentController@previewPDF')->name('previewPDF');

Route::group(['middleware' => ['auth:api,token,organisation']], function() {

    /*
    |--------------------------------------------------------------------------
    | Website API routes
    |--------------------------------------------------------------------------
    |
    */

    Route::get('permissions', ['uses' => 'AdminController@permissions'])->name('permissions');

    /*
    |--------------------------------------------------------------------------
    | Dictionary routes
    |--------------------------------------------------------------------------
    |
    |
    */
    Route::group(['prefix' => '/state', 'as' => 'state.'], function () {
        Route::get('', 'Rest\StateController@index')->name('index');
    });

    Route::group(['prefix' => '/country', 'as' => 'country.'], function() {
        Route::get('', 'Rest\CountryController@index')->name('index');
    });

    Route::group(['prefix' => '/field', 'as' => 'field.'], function() {
        Route::get('', 'Rest\FieldsController@index')->name('index');
        Route::get('{id}', 'Rest\FieldsController@show')->name('show')->where('id', '[0-9a-zA-Z]+');
    });

    Route::group(['prefix' => '/requirement', 'as' => 'requirement.'], function() {
        Route::get('', 'Rest\RequirementController@index')->name('index');
    });

    /*
    |--------------------------------------------------------------------------
    | Scholarships service routes
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => '/scholarship', 'as' => 'scholarship.'], function () {
        Route::get('', ['uses' => 'Rest\ScholarshipController@index'])->name('index');

        Route::get('/d/{domain}', 'Rest\ScholarshipController@showByDomain')->name('showByDomain');

        Route::post('/{id}/chooseWinners', 'Rest\ScholarshipController@chooseWinners')->name('chooseWinners');

        Route::post('/{id}/republish', 'Rest\ScholarshipController@republish')->name('republish');

        Route::group(['as' => 'related.application.'], function () {
            Route::get('/{id}/application', 'Rest\ScholarshipController@relatedApplications')->name('show');
            Route::get('/{id}/application/export', 'Rest\ScholarshipController@relatedApplicationsExport')->name('export');
        });

        Route::group(['as' => 'related.winner.'], function () {
            Route::get('/{id}/winner', 'Rest\ScholarshipController@relatedWinners')->name('show');
        });

        Route::group(['as' => 'related.fields.'], function () {
            Route::get('/{id}/fields', 'Rest\ScholarshipController@relatedFields')->name('show');
        });

        // Route::patch('{id}',            ['uses' => 'Rest\ScholarshipController@update'])->name('update');
        // Route::delete('{id}',           ['uses' => 'Rest\ScholarshipController@delete'])->name('delete');
    });

    Route::group(['prefix' => '/scholarship_template', 'as' => 'scholarship_template.'], function () {
        Route::get('', 'Rest\ScholarshipTemplateController@index')->name('index');
        Route::post('', 'Rest\ScholarshipTemplateController@createScholarship')->name('create');
        Route::delete('{id}', 'Rest\ScholarshipTemplateController@delete')->name('delete');

        Route::get('{id}', 'Rest\ScholarshipTemplateController@show')->name('show');
        Route::patch('{id}', 'Rest\ScholarshipTemplateController@updateScholarship')->name('update');
        Route::post('/{id}/publish', 'Rest\ScholarshipTemplateController@publish')->name('publish');

        Route::post('/recurrence_prediction', 'Rest\ScholarshipTemplateController@recurrencePrediction')->name('recurrencePrediction');

        Route::group(['as' => 'related.subscription.'], function () {
            Route::post('/{id}/subscription', 'Rest\ScholarshipTemplateController@relatedSubscriptionCreate')->name('create');
        });
        Route::group(['as' => 'related.content.'], function () {
            Route::get('/{id}/content', 'Rest\ScholarshipTemplateController@relatedContent')->name('show');
        });

        Route::group(['as' => 'related.scholarship.'], function () {
            Route::get('/{id}/scholarship', 'Rest\ScholarshipTemplateController@relatedScholarship')->name('show');
        });

        Route::group(['as' => 'related.website.'], function () {
            Route::get('/{id}/website', 'Rest\ScholarshipTemplateController@relatedWebsite')->name('show');
            Route::post('/{id}/website', 'Rest\ScholarshipTemplateController@relatedWebsiteCreate')->name('create');
            Route::patch('/{id}/website', 'Rest\ScholarshipTemplateController@relatedWebsiteUpdate')->name('update');
        });

        Route::group(['as' => 'related.iframe.'], function() {
            Route::get('/{id}/iframe', 'Rest\ScholarshipTemplateController@relatedIframes')->name('show');
        });

        Route::group(['as' => 'related.fields.'], function() {
            Route::get('/{id}/fields', 'Rest\ScholarshipTemplateController@relatedFields')->name('show');
            Route::put('/{id}/fields', 'Rest\ScholarshipTemplateController@relatedFieldsUpdate')->name('update');
        });

        Route::group(['as' => 'related.requirements.'], function() {
            Route::get('/{id}/requirements', 'Rest\ScholarshipTemplateController@relatedRequirements')->name('show');
            Route::put('/{id}/requirements', 'Rest\ScholarshipTemplateController@relatedRequirementsUpdate')->name('update');
        });
    });

    Route::group(['prefix' => '/scholarship_template_content', 'as' => 'scholarship_template_content.'], function() {
        Route::get('{id}', 'Rest\ScholarshipTemplateContentController@show')->name('show');
        Route::patch('{id}', 'Rest\ScholarshipTemplateContentController@update')->name('update');
    });

    Route::group(['prefix' => '/scholarship_template_field', 'as' => 'scholarship_template_field.'], function() {
        Route::post('', 'Rest\ScholarshipTemplateFieldController@create')->name('create');
        Route::post('{id}', 'Rest\ScholarshipTemplateFieldController@update')->name('update');
        Route::delete('{id}', 'Rest\ScholarshipTemplateFieldController@delete')->name('delete');
    });

    Route::group(['prefix' => '/scholarship_winner', 'as' => 'scholarship_winner.'], function () {
        Route::get('', 'Rest\ScholarshipWinnerController@index')->name('index');

        Route::get('{id}', 'Rest\ScholarshipWinnerController@show')->name('show');
        Route::post('', 'Rest\ScholarshipWinnerController@create')->name('create');
        Route::post('{id}', 'Rest\ScholarshipWinnerController@update')->name('update');
    });

    Route::group(['prefix' => 'scholarship_website', 'as' => 'scholarship_website.'], function () {
        Route::get('{id}', ['uses' => 'Rest\ScholarshipWebsiteController@show'])->name('show');
        Route::get('/d/{domain}', 'Rest\ScholarshipWebsiteController@showByDomain')->name('showByDomain');

        /**
         * TODO: Move this action to scholarship controller.
         */
        Route::group(['as' => 'related.winners.'], function () {
            Route::post('/{id}/winners', 'Rest\ScholarshipWebsiteController@relatedWinnersCreate')->name('create');
        });
    });

    Route::group(['prefix' => 'iframe', 'as' => 'iframe.'], function() {
        Route::get('{id}', ['uses' => 'Rest\IframeController@show'])->name('show');
        Route::post('', ['uses' => 'Rest\IframeController@create'])->name('create');
        Route::patch('{id}', ['uses' => 'Rest\IframeController@update'])->name('update');
        Route::delete('{id}', ['uses' => 'Rest\IframeController@delete'])->name('delete');
    });

    /*
    |--------------------------------------------------------------------------
    | Application service routes
    |--------------------------------------------------------------------------
    */

    Route::group(['prefix' => '/application', 'as' => 'application.'], function () {
        Route::get('', ['uses' => 'Rest\ApplicationController@index'])->name('index');
        Route::get('{id}', ['uses' => 'Rest\ApplicationController@show'])->name('show');
        Route::patch('{id}', ['uses' => 'Rest\ApplicationController@update'])->name('update');
        Route::delete('{id}', ['uses' => 'Rest\ApplicationController@delete'])->name('delete');
    });

    Route::group(['prefix' => '/application_batch', 'as' => 'application_batch.'], function() {
        Route::post('/', 'Rest\ApplicationBatchController@create')->name('create');
        Route::get('/{id}', 'Rest\ApplicationBatchController@show')->name('show');
        Route::delete('/{id}', 'Rest\ApplicationBatchController@show')->name('delete');

        Route::group(['as' => 'related.applications.'], function() {
            Route::get('/{id}/applications', 'Rest\ApplicationBatchController@relatedApplications')->name('show');
        });
    });

    Route::group(['prefix' => '/application_winner', 'as' => 'application_winner.'], function () {
        Route::get('', 'Rest\ApplicationWinnerController@index')->name('index');
        Route::get('{id}', 'Rest\ApplicationWinnerController@show')->name('show');
        Route::post('{id}', 'Rest\ApplicationWinnerController@update')->name('update');
        Route::get('{id}/face', 'Rest\ApplicationWinnerController@face')->name('face');
    });

    Route::group(['prefix' => '/application_file', 'as' => 'application_file.'], function () {
        Route::post('', 'Rest\ApplicationFileController@createFile')->name('create');
        Route::get('{id}/download', 'Rest\ApplicationFileController@download')->name('download');
        Route::get('{id}/file', 'Rest\ApplicationFileController@file')->name('file');
    });

    /*
    |--------------------------------------------------------------------------
    | User service routes
    |--------------------------------------------------------------------------
    */

    Route::group(['prefix' => '/user', 'as' => 'user.'], function () {
        Route::get('me', ['uses' => 'Rest\UserRestController@me'])->name('me');

        Route::get('{id}', ['uses' => 'Rest\UserRestController@show'])->name('show');
        Route::post('{id}', ['uses' => 'Rest\UserRestController@updateUser'])->name('update');

        Route::group(['as' => 'related.scholarships.'], function() {
            Route::get('{id}/scholarships', 'Rest\UserRestController@relatedScholarships')->name('show');
        });

        Route::group(['as' => 'related.tokens.'], function() {
            Route::get('{id}/tokens', 'Rest\UserRestController@relatedTokens')->name('show');
        });

        //    Route::get('',                  ['uses' => 'Rest\UserRestController@index'])->name('index');
        //    Route::get('{id}',              ['uses' => 'Rest\UserRestController@show'])->name('show');
        //    Route::post('',                 ['uses' => 'Rest\UserRestController@create'])->name('create');
        //    Route::delete('{id}',           ['uses' => 'Rest\UserRestController@delete'])->name('delete');

        //    Route::get('scholarship',       ['uses' => 'Rest\UserRestController@assignedScholarship'])->name('assignedScholarship');
        //    Route::post('{id}/scholarship', ['uses' => 'Rest\UserRestController@assignScholarship'])->name('assignScholarship');
    });

    Route::group(['prefix' => '/user_token', 'as' => 'user_token.'], function () {
        Route::post('', 'Rest\UserTokenController@create')->name('create');
        Route::patch('{id}', 'Rest\UserTokenController@update')->name('update');
        Route::delete('{id}', 'Rest\UserTokenController@delete')->name('delete');
    });

    Route::group(['prefix' => '/user_tutorial', 'as' => 'user_tutorial.'], function () {
        Route::get('{id}', ['uses' => 'Rest\UserTutorialController@show'])->name('show');
        Route::patch('{id}', ['uses' => 'Rest\UserTutorialController@update'])->name('update');
        Route::put('{id}', ['uses' => 'Rest\UserTutorialController@update']);
    });

    Route::group(['prefix' => '/role', 'as' => 'role.'], function () {
        Route::get('', ['uses' => 'Rest\RoleRestController@index'])->name('index');
        Route::get('{id}', ['uses' => 'Rest\RoleRestController@show'])->name('show');
        Route::post('', ['uses' => 'Rest\RoleRestController@create'])->name('create');
        Route::patch('{id}', ['uses' => 'Rest\RoleRestController@update'])->name('update');
        Route::delete('{id}', ['uses' => 'Rest\RoleRestController@delete'])->name('delete');
    });

    /*
    |--------------------------------------------------------------------------
    | Settings service routes
    |--------------------------------------------------------------------------
    */

    Route::group(['prefix' => '/settings', 'as' => 'settings.'], function() {
        Route::get('', ['uses' => 'Rest\SettingsController@index'])->name('index');
        Route::get('{id}', ['uses' => 'Rest\SettingsController@show'])->name('show')->where('id', '[\.\w]+');
        Route::patch('{id}', ['uses' => 'Rest\SettingsController@update'])->name('update')->where('id', '[\.\w]+');
    });

    /*
    |--------------------------------------------------------------------------
    | Organisation service routes
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => '/organisation', 'as' => 'organisation.'], function () {
        Route::get('/', ['uses' => 'Rest\OrganisationController@index'])->name('index');
        Route::post('/', ['uses' => 'Rest\OrganisationController@create'])->name('create');
        Route::patch('/{id}', ['uses' => 'Rest\OrganisationController@update'])->name('update');
        Route::get('/{id}', ['uses' => 'Rest\OrganisationController@show'])->name('show');
        Route::get('me', ['uses' => 'Rest\OrganisationController@me'])->name('me');

        /**
         * /organisation/1/scholarships
         */
        Route::group(['as' => 'related.scholarships.'], function () {
            Route::get('/{id}/scholarships', ['uses' => 'Rest\OrganisationController@relatedScholarships'])->name('show');
        });

        /**
         * /organisation/1/winners
         */
        Route::group(['as' => 'related.winners.'], function () {
            Route::get('/{id}/winners', ['uses' => 'Rest\OrganisationController@relatedWinners'])->name('show');
        });
    });

});

Route::get('/{any?}', 'AdminController@notFound')->where('any', '.*');
