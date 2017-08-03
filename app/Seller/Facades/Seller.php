<?php
/**
 * Created by PhpStorm.
 * User: zhanqian
 * Date: 2017/6/12
 * Time: 11:40
 */

namespace App\Seller\Facades;

use Illuminate\Support\Facades\Facade;

class Seller extends Facade
{
    protected static function getFacadeAccessor() {
        return 'seller';
    }
}