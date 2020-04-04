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

Route::get('/', function () {
    return view('/auth/login');
});

Auth::routes();

Route::get('/home', 'HomeController@master')->name('home');
Route::get('/user-management', 'HomeController@master')->name('user-management');
Route::get('/profile/{username}', 'HomeController@master')->name('profile');
Route::get('/my-account', 'HomeController@master')->name('my-account');
Route::get('/view-account', 'HomeController@master')->name('view-account');
Route::get('/update-user/{user_id}', 'HomeController@master')->name('update-user');
Route::get('/account-setting/{user_id}', 'HomeController@master')->name('account-setting');
Route::get('/register-user', 'HomeController@master')->name('register-user');
