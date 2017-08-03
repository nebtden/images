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
Route::get('/',"IndexController@index")->name('index');

/*运单轨迹查询页面*/
Route::get('/search/{track_number?}',"SearchController@search");



Route::get('/test',"TestController@test")->name('test');
Route::get('/guzzle',"TestController@guzzle")->name('guzzle');


//卖家登陆路由
Auth::routes();

//普通页面
Route::get('/home', 'Seller\HomeController@index');
Route::middleware(['pjax'])->resource('/seller/freight', 'Seller\FreightController');
Route::middleware(['pjax'])->resource('/seller/transaction', 'Seller\TransactionController');
Route::middleware(['pjax'])->resource('/seller/transition', 'Seller\TransitionController');
Route::middleware(['pjax'])->resource('/seller/storage', 'Seller\StorageChargeController');
Route::middleware(['pjax'])->resource('/seller/store', 'Seller\StoreController');

Route::middleware(['pjax'])->resource('/seller/deduction', 'Seller\DeductionController');
Route::middleware(['pjax'])->get('/seller/day', 'Seller\DeductionController@day');
Route::middleware(['pjax'])->get('/seller/week', 'Seller\DeductionController@week');
Route::middleware(['pjax'])->get('/seller/month', 'Seller\DeductionController@month');

// 设置密码等操作
//Route::middleware('pjax')->resource('/seller/setting', 'Seller\SettingController',['as'=>'/seller/setting']);
//Route::put('/seller/setting/update', 'Seller\SettingController@update');


Route::get('logout', 'Auth\LoginController@logout')->name('logout');