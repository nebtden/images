<?php

/**
 * 用于仓库端下发数据验证
 */

namespace App\Http\Controllers;

use App\Dispatch\WaybillDeliveryfee;
use App\Dispatch\WaybillUpdate;
use App\Jobs\ClientSend;
use Illuminate\Http\Request;
use App\Dispatch\WaybillTrackingadd;

class TmsController extends Controller
{
     /**
         *  Handle an incoming request.
         *  @param  \Illuminate\Http\Request  $request
         *  @return mixed
         */
    public function v1(Request $request){
        //根据不同的method参数，  走不同的处理方式
        $method = $request->input('method');
        switch ($method){

            //更新订单状态
            case 'nos.waybill.status.update':
                $update = new  WaybillUpdate();
                return $update->index(\GuzzleHttp\json_decode($request->input('data'),true),'tms');

            //添加物流轨迹
            case 'nos.waybill.tracking.add':
                $waybill = new  WaybillTrackingadd();
                return $waybill->index(\GuzzleHttp\json_decode($request->input('data'),true),'tms');
            case 'nos.waybill.fee':
                $waybill = new  WaybillDeliveryfee();
                $data = urldecode($request->input('data'));
                return $waybill->index(\GuzzleHttp\json_decode($data,true));

            default:
                return $this->return_msg_list;
        }

    }



//    public function
}
