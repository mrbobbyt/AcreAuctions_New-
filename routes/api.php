<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/***** Authentication *****/

Route::post('register', 'API\v1\AuthController@register');

Route::post('login', 'API\v1\AuthController@login');

Route::get('logout', 'API\v1\AuthController@logout')
    ->middleware('jwt.verify');

Route::post('reset', 'API\v1\AuthController@resetPassword')
    ->middleware('jwt.verify');

Route::post('forgot', 'API\v1\AuthController@forgotPassword');

Route::get('', 'API\v1\AuthController@index')
    ->name('home');


/********** User **********/

Route::get('profile', 'API\v1\UserController@profile')
    ->middleware('jwt.verify');

Route::get('user/view/{id}', 'API\v1\UserController@view');

Route::post('user/update/{id}', 'API\v1\UserController@update')
    ->middleware('jwt.verify');

Route::get('user/delete/{id}', 'API\v1\UserController@delete')
    ->middleware('jwt.verify');


/********* Seller *********/

Route::get('seller/{slug}', 'API\v1\SellerController@view');

Route::post('seller/create', 'API\v1\SellerController@create')
    ->middleware('jwt.verify');

Route::post('seller/update/{id}', 'API\v1\SellerController@update')
    ->middleware('jwt.verify');

Route::get('seller/delete/{id}', 'API\v1\SellerController@delete')
    ->middleware('jwt.verify');


/********* Admin *********/

Route::post('admin', 'API\v1\AdminController@verify')
    ->middleware('jwt.verify', 'admin');


/******** Listing ********/

Route::get('land-for-sale/{slug}', 'API\v1\ListingController@view');

Route::post('land-for-sale/create', 'API\v1\ListingController@create')
    ->middleware('jwt.verify');

Route::post('land-for-sale/update/{id}', 'API\v1\ListingController@update')
    ->middleware('jwt.verify');

Route::get('land-for-sale/delete/{id}', 'API\v1\ListingController@delete')
    ->middleware('jwt.verify');


/*Route::get('reset/{token}', function($token) {
    $model = \App\Models\PasswordResets::where('token', '=', $token)->first();
//    return new \App\Http\Resources\UserResource($model->user);
    return response()->json(['role' => $model->user->getRole]);
});*/
