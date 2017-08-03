<?php

/**
 * 用于平台端下发数据验证
 */

namespace App\Http\Controllers;

use App\Dispatch\GoodsGet;
use App\Dispatch\OutorderCancel;
use App\Dispatch\OutorderCreate;
use App\Dispatch\SellerBalanceGet;
use App\Dispatch\SellerGet;
use App\Dispatch\WarehouseGet;
use App\Dispatch\WaybillGet;
use App\Dispatch\WaybillSellerdelivery;
use App\Dispatch\WaybillTrackingGet;
use App\Jobs\ClientSend;
use Illuminate\Http\Request;
use App\Dispatch\WaybillCreate;
use App\Dispatch\WaybillCancel;

class ClientController extends Controller
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

            case 'nos.waybill.create':
                $waybillcreate = new  WaybillCreate();
                return $waybillcreate->index(\GuzzleHttp\json_decode($request->input('data'),true));

            case 'nos.waybill.cancel':
                $obj = new WaybillCancel();
                return $obj->index(\GuzzleHttp\json_decode($request->input('data'),true),'client');

            case 'nos.waybill.get':
                $waybill = new  WaybillGet();
                return $waybill->index(\GuzzleHttp\json_decode($request->input('data'),true));

            //卖家发货确认
            case 'nos.waybill.sellerdelivery':
                $waybilldelivery = new  WaybillSellerdelivery();
                return $waybilldelivery->index(\GuzzleHttp\json_decode($request->input('data'),true));

            //海外仓出库单创建
            case 'nos.wms.outorder.create':
                $outorder = new OutorderCreate();
                return $outorder->index(\GuzzleHttp\json_decode($request->input('data'),true));

            case 'nos.wms.outorder.cancel':
                $outorder = new OutorderCancel();
                return $outorder->index(\GuzzleHttp\json_decode($request->input('data'),true),'client');

            //不需要验证，在中间件跳过验证
            case 'nos.waybill.tracking.get':
                $tracking = new WaybillTrackingGet();
                return $tracking->index(\GuzzleHttp\json_decode($request->input('data'),true));
                break;

            case 'nos.wms.warehouse.get':
                $warehouse = new  WarehouseGet();
                return $warehouse->index(\GuzzleHttp\json_decode($request->input('data'),true));

            case 'nos.goods.get':
                $goodsget = new  GoodsGet();
                return $goodsget->index(\GuzzleHttp\json_decode($request->input('data'),true));
            case 'nos.seller.get':
                $sellget = new  SellerGet();
                return $sellget->index(\GuzzleHttp\json_decode($request->input('data'),true));
            case 'nos.balance.get':
                $sellerbalance = new SellerBalanceGet();
                return $sellerbalance->index(\GuzzleHttp\json_decode($request->input('data'),true));
            default:

                return $this->return_msg_list;
        }

    }



//    public function
}
