<?php

namespace App\Http\Controllers;

use App\Helpers;
use App\Models\Freight;
use App\Models\Seller;
use App\Models\Settings;
use App\Models\User;
use App\Repositories\ApilogRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class TestController extends Controller
{
    public function index(Request $request, ApilogRepository $apilogRepository)
    {
//        $waybillre = $this->app['WaybillRepository'];
//        dd($waybillre);

        $inputs = $request->all();
        $inputs['return'] = 'sdffsdf';
        $inputs['data'] = 111;
        $inputs['type'] = 'cancel';
        $inputs['sign'] = "cancelasdsdf";
        $inputs['url'] = 'cancelasds';

        $apilogRepository->store($inputs);
        return [4, 5];
    }


    public function guzzle()
    {
//        $client = new Client();
//
//        //$res = $client->request('GET', 'test.kiliexpress.com/accept');
//        $request = new guzzlerequest('post', '52.196.66.11/index/Login/check');
//        $response = $client->send($request,
//            [
//                'form_params' => [
//                    'username' => 'admin',
//                    'password'=>'admin'
//                ]
//
//            ]
//
//        );
        //Log::debug('notice');
        //$this->dispatch(new SendtoWms());
        //return $response;
        $model = \App\Models\Tms::where(['app_key' => 'shenzhen'])->first();

        $data['store_id'] = '454545';
        $data['countryCode'] = 'ke';
        $data['receiveCode'] = 'ke01';
        $data['expressname'] = '物流公司';
        $data['expressno'] = '2512365225';
        $data['packageWeight'] = '10.00';
        $data['senderName'] = 'fdsfdsf';
        $data['senderAddr'] = 'fdsfdsf';
        $data['senderCompany'] = 'fdsfdsf';
        $data['senderPhone'] = 'fdsfdsf';
        $data['goodsListInfo'] = [['waybillNo' => 'ke00003',//运单号
            'orderWeight' => 10.23,//运单重量
            '123456789' => [//这里的key为订单号 order_sn
                ['categoryOne' => 'testClassOne1', 'categoryTwo' => 'testClassTwo1', 'categoryThree' => 'testClassTree1', 'quantity' => 4, 'skuEnName' => 'skuTest1', 'skuCnName' => '', 'skuPrice' => 11.2, 'skuWidth' => 11.11, 'skuHeight' => 0.22, 'skuLength' => 10.23, 'skuWeight' => 2.03], ['categoryOne' => 'testClassOne2', 'categoryTwo' => 'testClassTwo2', 'categoryThree' => 'testClassTree2', 'quantity' => 3, 'skuEnName' => 'skuTest2', 'skuCnName' => '', 'skuPrice' => 12.2, 'skuWidth' => 11.11, 'skuHeight' => 0.22, 'skuLength' => 10.23, 'skuWeight' => 2.03], ['categoryOne' => 'testClassOne3', 'categoryTwo' => 'testClassTwo3', 'categoryThree' => 'testClassTree4', 'quantity' => 2, 'skuEnName' => 'skuTest3', 'skuCnName' => '', 'skuPrice' => 13.2, 'skuWidth' => 11.11, 'skuHeight' => 0.22, 'skuLength' => 10.23, 'skuWeight' => 2.03]]]];

        return $this->sendToTms($model, $data);
    }


    public function test1(){
        $freight = Freight::findOrFail(1);
        $freight->goodsclass->class_name_en;
        return $freight;
    }


    public function test2()
    {
         $user = User::findOrFail(1);

             $seller = $user->seller;
         return $seller;
    }

    public function test3(){

        $moneylist = Settings::where('group','money')
            ->get();
        $return = [];
        foreach ($moneylist as $value){

            $return[$value['id']]=$value['key'];

        }
        return $return;

    }

    public function tests(){
        $deleted = DB::table('kili_freight')->where([
            ['date','=','2017-06-09'],
            ['type_id','=',1],
                [ 'charge_type','=',0],
                    ['money_type','=',0],
                    ['index','<>',1],
        ])->delete();
    }

    public function test9(){
        //DB::beginTransaction();






        Excel::load(resource_path().'/upload/'.'store.xlsx', function($reader) {

            $password = bcrypt('feisuda');
            Session(['password'=>$password]);
            // Getting all results
            $reader->each(function($sheet) {
                // Loop through all rows


                foreach ($sheet as $row){

                        //检测多个用户一个店铺的情况，跳过
                        $seller = Seller::where(['store_id'=>$row['store_id'],'type_id'=>1])->get()->toArray();
                        if($seller){
                            continue;
                        }

                        $user_data = [
                            'name'=>$row['name'],
                            'type_id'=>1,
                            'email'=>$row['member_email']?:'111',
                            'balance'   => 0,
                            'password'=>Session('password')
                        ];
                        $user = new User();
                        $user->name = $row['name'];
                        $user->type_id = 1;
                        $user->email = $row['member_email']?:'';
                        $user->balance = 0;
                        $user->password = Session('password');

                        $user->save();

                        $seller_data = [
                            'store_id'     => $row['store_id'],
                            'type_id'       => 1,
                            'store_name'    => $row['store_name'],

                            'user_id'       =>$user->id,
                        ];

                        $store_id = Seller::insert($seller_data);



                };

            });

        });
        echo 'success!!!!';
       // DB::commit();

    }


    public function test8(){
        $affected = DB::update('UPDATE `kili_freight` f
INNER  JOIN kili_sellers s ON s.type_id = f.type_id and
 s.store_id = f.store_id
set f.user_id = s.user_id
where f.user_id = 0 ;');
        return $affected;
    }


    public function test(){
        $time = Helpers::weekday();

        return date('H:i:s');
        dd($time);
    }



}
