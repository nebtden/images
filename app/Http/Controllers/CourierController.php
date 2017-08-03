<?php

namespace App\Http\Controllers;

use App\Dispatch\WaybillDeliveryfee;
use App\Dispatch\WaybillRiderpay;
use App\Dispatch\WaybillTrackingadd;
use App\Dispatch\WaybillUpdate;
use Illuminate\Http\Request;
use App\Dispatch\AbnormalWaybillAdd;
class CourierController extends Controller
{
    /**
     * 接收快递公司客户端的请求，并更改记录
     *  Handle an incoming request.
     *  @param  \Illuminate\Http\Request  $request
     *  @return mixed
     */
    public function v1(Request $request){
        $method = $request->input('method');

        switch ($method){
            case 'nos.waybill.status.update':
                $update = new  WaybillUpdate();
                return $update->index(\GuzzleHttp\json_decode($request->input('data'),true),'courier');

            case 'nos.waybill.riderpay':
                $riderpay = new WaybillRiderpay();
                return $riderpay->index(\GuzzleHttp\json_decode($request->input('data'),true));

            case 'nos.exception':
                $unnormal = new AbnormalWaybillAdd();
                return $unnormal->index(\GuzzleHttp\json_decode($request->input('data'),true));

            case 'nos.waybill.tracking.add':
                $waybill = new  WaybillTrackingadd();
                return $waybill->index(\GuzzleHttp\json_decode($request->input('data'),true),'courier');
            case 'nos.waybill.fee':
                $waybill = new  WaybillDeliveryfee();
                $data = urldecode($request->input('data'));
                return $waybill->index(\GuzzleHttp\json_decode($data,true));
            default:
                return $this->return_msg_list;
        }
    }
}
