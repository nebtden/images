<?php

/**
 * 验证签名,是否合法请求
 * 后续如果更改，更改中间件即可
 * @author simon.zhang
 */
namespace App\Http\Middleware;


use App\Models\Apilog;
use App\Repositories\ApilogRepository;
use Closure;
use Exception;
use Illuminate\Http\Response;


class Seller
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
            $client= \App\Models\Client::where('app_key', $name)->first();
            if(!$client){
                 throw  new Exception('token error');
            }


            //数据进行签名验证
            $mytoken = MD5($client->app_secret.$request->input('data').$client->app_secret);
            if(strtoupper($mytoken)!=$request['sign']){
                 throw  new Exception('client token error');
            }
            Session(['type_id'=>$client->id]);
            Session(['short_name'=>$client->short_name]);

            //api，无session操作。。无法进数据库
            //session()

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
        $inputs = $request->all();
        $inputs['return'] = $response->getOriginalContent();

        $apilog = \App::make('ApilogRepository');
        $apilog->store($inputs);



    }
}
