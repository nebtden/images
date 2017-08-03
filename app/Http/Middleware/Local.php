<?php

namespace App\Http\Middleware;


use Illuminate\Support\Facades\App;
use Closure;
use Illuminate\Support\Facades\Session;

class Local
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
        if(Session::has('local'))
        {
            $lang = Session::get('local');
            App::setLocale($lang);
        }

        return $next($request);
    }
}