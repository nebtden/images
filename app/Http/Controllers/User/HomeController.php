<?php

namespace App\Http\Controllers\User;


use App\Seller\Facades\Seller;
use App\Seller\Layout\Content;


class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Seller::content(function (Content $content) {

            $content->header('');
            $content->description('欢迎来到宅圣...');

        });
    }
}
