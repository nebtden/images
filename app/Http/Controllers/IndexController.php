<?php



namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{

    /**
     *  Handle an incoming request.
     *  @param  \Illuminate\Http\Request  $request
     *  @return mixed
     */
    public function index(Request $request){
        $images = DB::table('images')->simplePaginate(6);
        return view('index', ['images' => $images]);

    }



//    public function
}
