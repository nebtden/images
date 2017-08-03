<?php

namespace App\Http\Controllers\Seller;


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

            $content->header('Dashboard');
            $content->description('Welcome to nilo...');





        });
    }
}
