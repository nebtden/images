<?php

namespace App\Admin\Controllers;



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;


class ApiController extends Controller
{
    public function users(Request $request){
//        return $_GET;
        $q = $request->get('q');

        return User::where('name', 'like', "%$q%")->paginate(null, ['id', 'name as text']);
    }
}
