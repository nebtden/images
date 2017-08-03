<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Models\Record;

class SearchController extends Controller
{

    /**
     *  @param    $tracking_number  string 跟踪号
     *  @create by Coffee
     *  @return  mixed
     **/
    public function search($tracking_number=''){

        //检测是否有参数
        if(!$tracking_number){
            return view('search');
        }

        //检测是否存在数据库
        //查找相关信息
        $waybillRepository = \App::make('WaybillRepository');
        if(!$waybillRepository->checkWaybill($tracking_number)){
            $this->return_msg_list['error'] = 'lost track nubmber!';
            return view('search',['list'=>$this->return_msg_list]);
        }


        $list = Track::where(['tracking_number'=>$tracking_number])->get();
        $this->return_msg_list['response'] = $list;
        return view('search',['list'=>$this->return_msg_list]);

    }
}
