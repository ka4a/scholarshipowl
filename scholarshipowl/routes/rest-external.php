<?php

Route::group(['prefix' => 'rest-external', 'as' => 'rest-external::', ], function() {
    Route::group(['prefix' => 'v1', 'as' => 'v1.'], function() {
        /* OpenAPI/swagger API documentation */
        Route::group(['middleware' => 'auth:admin'], function() {
            Route::get('/doc', ['uses' => 'RestExternal\OpenAPIController@index', 'as' => 'doc']);
            Route::get('/doc/file', ['uses' => 'RestExternal\OpenAPIController@getFileContent', 'as' => 'doc.file']);
            Route::get('/doc/{fileName}', ['uses' => 'RestExternal\OpenAPIController@getIncludeFileContent'])->where('fileName', '.*\.(yml|yaml)');
        });


        Route::get('/account-fields', ['uses' => 'RestExternal\AccountFieldsController@listAccountFields', 'as' => 'list-account-fields']);
        Route::post('/account-fields', ['uses' => 'RestExternal\AccountFieldsController@getAndRefreshAccountFields', 'as' => 'get-account-fields']);
    });
});
