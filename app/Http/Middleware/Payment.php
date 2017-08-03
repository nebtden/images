<?php

/**
 * 验证lipapay签名,是否合法请求
 * 后续如果更改，更改中间件即可
 * @author simon.zhang
 */
namespace App\Http\Middleware;


use Closure;
use Exception;


class Payment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //$this->apilog = $apilog;
        $return = [
            "returnCode"=>"0001",
            "returnDesc"=>"token error",
            "returnFlag"=>"1" ,
        ];

        try{
            $payment_info = \App\Models\Payment::find(1);

            $merchantId		= $request['merchantId'];//商户号
            $signType		= $request['signType'];//签名类型
            $sign			= $request['sign']; //签名
            $merchantOrderNo= $request['merchantOrderNo'];//商户订单号
            $orderId		= $request['orderId']; //	订单号
            $status			= $request['status']; //订单状态
            $paymentMethod	= $request['paymentMethod'];//支付方式
            $paymentChannel	= $request['paymentChannel'];//支付渠道
            $orgTransId         = $request['orgTransId'];//trans_id
            $amount=$pay_amount =$request['amount'];//amount

            $datas = $request->all();

            $check_data=array(
                'merchantId'=>$datas['merchantId'],//商户号
                'signType'=>$datas['signType'],//签名类型
                'merchantOrderNo'=>$datas['merchantOrderNo'],//商户订单号
                'orderId'=>$datas['orderId'],//	订单号
                'status'=>$datas['status'],//订单状态
                'paymentMethod'=>$datas['paymentMethod'],//支付方式
                'paymentChannel'=>$datas['paymentChannel'],//支付渠道
                'orgTransId'=>$datas['orgTransId'],//trans_id
                'amount'=>$datas['amount']//amount
            );


            //参数错误
            if(!$merchantId || !$signType || !$sign || !$merchantOrderNo || !$orderId || !$status || !$paymentMethod || !$amount || !$paymentChannel || !$orgTransId){
                throw  new Exception('lost arguments');
            }


            $check_sign=$this->sign($check_data,$payment_info->key);

            if($check_sign!=$request['sign']){
                //throw  new Exception('lipapay token error');
            }



        }catch (\Exception $e){
            $return['returnDesc'] = $e->getMessage();
            //插入到日志记录表，便于分析
            $input = $request->all();
            $input['return'] = $return;
            $input['data'] = \GuzzleHttp\json_encode($request->all());
            $input['sign'] = $request['sign'];

            $lipapaylog = \App::make('ApilogRepository');
            $lipapaylog->store($input);

            return response()->json($return);
        }

        //验证通过，下一个路由
        return $next($request);
    }


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return mixed
     */

    public function terminate($request, $response)
    {
        //
        //插入到日志记录表，便于分析
        $input = $request->all();
        $input['return'] = $response->getOriginalContent();

        $apilog = \App::make('ApilogRepository');
        $apilog->store($input);
    }

    /**
     * @param $sign_str array
     * @param $app_key string
     * 生成秘钥
     * @return string
     */
    private function sign($sign_str,$app_key){
        $md5='';
        ksort($sign_str);
        $data=array_filter($sign_str);
        foreach ($data as $key => $val) {
            $md5.=$key.'='.$val.'&';
        }
        $md5=htmlspecialchars_decode($md5);
        $md5=substr($md5,0,strlen($md5)-1);
        $md5.=$app_key;
        $sign=md5($md5);
        return $sign;
    }
}
