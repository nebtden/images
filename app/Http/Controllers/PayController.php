<?php

/**
 * 用于支付端的方法验证
 */

namespace App\Http\Controllers;


use App\Dispatch\WaybillOnlinepay;
use Illuminate\Http\Request;

class PayController extends Controller
{

    /**
         *  Handle an incoming request.
         *  @param  \Illuminate\Http\Request  $request
         *  @return mixed
         */
    public function v1(Request $request){

        $pay = new WaybillOnlinepay();
        return $pay->index($request);


    }



}
