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
Route::get('/home', 'User\HomeController@index');
Route::middleware(['pjax'])->resource('/user/record', 'User\RecordController');

// 设置密码等操作
//Route::middleware('pjax')->resource('/user/setting', 'User\SettingController',['as'=>'/user/setting']);
//Route::put('/user/setting/update', 'User\SettingController@update');


Route::get('logout', 'Auth\LoginController@logout')->name('logout');