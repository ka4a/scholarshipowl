<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function() {

    Route::post('login', 'AuthController@login')->name('login');
    Route::post('logout', 'AuthController@logout')->name('logout');
    Route::post('registration', 'AuthController@registration')->name('registration');

    Route::group(['prefix' => 'google'], function() {
        Route::post('', 'AuthController@googleAuth')->name('google');
        Route::get('/login', 'AuthController@googleLogin')->name('google.login');
    });

    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::post('password/reset', 'ResetPasswordController@reset')->name('password.update');

});

Route::group(['prefix' => '/winner-information/{id}'], function() {
    Route::get('', 'Front\WinnerInformationController@winner')
        ->where('id', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}')
        ->name('winner-information');

    Route::get('/affidavit.pdf', 'Front\WinnerInformationController@affidavit')
        ->name('winner-information.affidavit');
});

Route::post('/preview', 'Front\PreviewController@index')->name('preview');

/**
 * Admin controller
 */
Route::get('/login', 'AdminController@index')->name('login');
Route::get('/password/reset/{token}', 'AdminController@index')->name('password.reset');

Route::get('/{any?}', 'AdminController@index')
    ->where('any', '.*')
    ->name('index');

//
//Route::get('/', 'IndexController@index');
//Route::get('/login', 'IndexController@index');
//Route::get('/registration', 'IndexController@index');

///**
// * VueJS routes
// */
//Route::get('/{any?}', 'IndexController@index')
//    ->where('any', '.*')
//    ->name('index');

