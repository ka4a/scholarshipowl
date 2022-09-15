<?php

// FRONT -----------------------------------------------------------------------

Route::group([
    'as'        => 'front.',
    'namespace' => 'Front',
], function () {

    Route::get('/', [
        'as'   => 'index',
        'uses' => 'PageController@index'
    ]);

    // FEATURES ----------------------------------------------------------------
    Route::group([
        'as' => 'features.',
    ], function () {

        Route::get('/features', [
            'as'   => 'index',
            'uses' => 'PageController@features'
        ]);

        Route::get('/features/courses', [
            'as'   => 'courses',
            'uses' => 'PageController@courses'
        ]);

        Route::get('/features/admissions-coaching', [
            'as'   => 'admissions-coaching',
            'uses' => 'PageController@admissionsCoaching'
        ]);

        Route::get('/features/essay-assistance', [
            'as'   => 'essay-assistance',
            'uses' => 'PageController@essayAssistance'
        ]);

        Route::get('/features/interview-preparation', [
            'as'   => 'interview-preparation',
            'uses' => 'PageController@interviewPreparation'
        ]);

        Route::get('/features/personalized-scholarships-list', [
            'as'   => 'personalized-scholarships-list',
            'uses' => 'PageController@personalizedScholarshipsList'
        ]);

        Route::get('/features/guidance-for-parents', [
            'as'   => 'guidance-for-parents',
            'uses' => 'PageController@guidanceForParents'
        ]);

    });

    Route::get('/faq', [
        'as'   => 'faq',
        'uses' => 'PageController@faq'
    ]);

    Route::get('/book-free-consultation', [
        'as'   => 'book-free-consultation',
        'uses' => 'PageController@bookFreeConsultation'
    ]);

    Route::get('/contact-us', [
        'as'   => 'contact.get',
        'uses' => 'PageController@getContact'
    ]);

    Route::post('/contact-us', [
        'as'   => 'contact.post',
        'uses' => 'PageController@postContact'
    ]);

    Route::post('/contact-us/coaching', [
        'as'   => 'contact-coaching.post',
        'uses' => 'PageController@postContactCoaching'
    ]);

    Route::get('/about-us', [
        'as'   => 'about-us',
        'uses' => 'PageController@aboutUs'
    ]);

    Route::get('/privacy-policy', [
        'as'   => 'privacy-policy',
        'uses' => 'PageController@privacyPolicy'
    ]);

    Route::get('/terms-of-use', [
        'as'   => 'terms-of-use',
        'uses' => 'PageController@termsOfUse'
    ]);

    Route::get('/pricing', [
        'as'   => 'pricing',
        'uses' => 'PageController@pricing'
    ]);

    Route::get('/sitemap', [
        'as'   => 'sitemap',
        'uses' => 'PageController@sitemap'
    ]);

});
