<?php

/**
 * 强制进行更改密码
 * 后续如果更改，更改中间件即可
 * @author simon.zhang
 */
namespace App\Http\Middleware;


use Closure;
use Illuminate\Support\Facades\Auth;


class ResetPassword
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
        //dd('testt');
         $user = Auth::user();
//         if(!$user->is_reset_password){
//             return redirect('/seller/setting/'.$user->id.'/edit');
//         }


        //验证通过，下一个路由
        return $next($request);
    }


}
