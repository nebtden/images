<?php

namespace App\Http\Middleware;

use Closure;
use Exception;

class Courier
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
            'status'=>'fail',
            'error'=>'',
            'msgid'=>'',
            'response'=>[

            ]
        ];



        try{
            //验证token
            if(!$request->input('sign')){
                throw  new Exception('missing token');
            }

            //验证签名
            $name = $request->input('app_key');
            if(!$name){
                throw  new Exception('missing appkey');
            }

            //数据库不存在此平台值
            $courier = \App\Models\Courier::where('app_key', $name)->first();
            if(!$courier){
                 throw  new  Exception('token error');
            }

            $mydata = urldecode($request->input('data'));

            //数据进行签名验证
            $mytoken = MD5($courier->app_secret.$mydata.$courier->app_secret);
            if(($mytoken)!=strtolower($request['sign'])){
                //throw  new Exception('courier token error');
            }


        }catch (\Exception $e){
            //插入到日志记录表，便于分析
            $inputs = $request->all();
            $return['error'] = $e->getMessage();
            $apilog = \App::make('ApilogRepository');
            $inputs['return'] = $return;
            $apilog->store($inputs);

            return response()->json($return);
        }


        //验证通过，下一个路由
        return $next($request);

    }


    public function terminate($request, $response)
    {
        //
        //插入到日志记录表，便于分析
        $inputs = $request->all();
        $inputs['return'] = $response->getOriginalContent();

        $apilog = \App::make('ApilogRepository');
        $apilog->store($inputs);
    }


}
