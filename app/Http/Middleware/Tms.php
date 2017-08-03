<?php

/**
 * 验证lipapay签名,是否合法请求
 * 后续如果更改，更改中间件即可
 * @author simon.zhang
 */
namespace App\Http\Middleware;


use Closure;
use Exception;


class Tms
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
            //验证签名
            if(!$request->input('sign')){
                throw  new Exception('missing sign');
            }

            //验证签名
            $app_key = $request->input('app_key');
            if(!$app_key){
                throw  new Exception('missing appkey');
            }

            //数据库不存在此平台值
            $tms= \App\Models\Tms::where('app_key', $app_key)->first();
            if(!$tms){
                throw  new Exception('app_key error');
            }

            //数据进行签名验证(同那边的方式)
            //数据进行签名验证
            $mytoken = MD5($tms->app_secret.$request->input('data').$tms->app_secret);

            if($mytoken!=strtolower($request->input('sign'))){
                throw  new Exception('sign error ');
            }
        }catch (\Exception $e){
            //插入到日志记录表，便于分析
            $return['error'] = $e->getMessage();

            $inputs = $request->all();
            $inputs['return'] = $return;
            $apilog = \App::make('ApilogRepository');
            $apilog->store($inputs);

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
        //插入到日志记录表，便于分析
        $inputs = $request->all();
        $inputs['return'] = $response->getOriginalContent();

        $apilog = \App::make('ApilogRepository');
        $apilog->store($inputs);
    }
}
