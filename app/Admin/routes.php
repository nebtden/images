<?php

use Illuminate\Routing\Router;

Admin::registerHelpersRoutes();

Route::group([
    'prefix'        => config('admin.prefix'),
    'namespace'     => Admin::controllerNamespace(),
    'middleware'    => ['web', 'admin'],
], function (Router $router) {
    $router->get('deduction/day', 'DeductionController@day');
    $router->get('deduction/week', 'DeductionController@week');
    $router->get('deduction/month', 'DeductionController@month');

    $router->get('/', 'HomeController@index');
    $router->resource('waybill', 'WaybillController');
    $router->resource('example', 'ExampleController');
    $router->resource('unnormal', 'UnnormalController');
    $router->resource('freight', 'FreightController');
    $router->resource('goods-class', 'GoodsClassController');
    $router->resource('storage-charge', 'StorageChargeController');
    $router->resource('transaction', 'TransactionController');
    $router->resource('transit-charge', 'TransitionController');
    $router->resource('settings', 'SettingsController');
    $router->resource('seller', 'SellerController');
    $router->resource('store', 'StoreController');
    $router->resource('user', 'UserController');
    $router->resource('deduction', 'DeductionController');
    $router->get('api/users', 'ApiController@users');



    // $router->get('/waybill/{id}/edit', 'WaybillController@edit');

});


