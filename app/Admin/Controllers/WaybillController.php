<?php

namespace App\Admin\Controllers;

use App\Models\ReceiverInfo;
use App\Models\SenderInfo;
use App\Models\Waybill;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\InfoBox;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Widgets\Box;

class WaybillController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('WaybillNumber');
            $content->description('Lists');

            $content->body($this->grid());
        });
    }

    public function show($id){
        return Admin::content(function (Content $content) use ($id){

            $content->header('header');
            $content->description('description');


            $waybill = \App::make('WaybillRepository');
            $waybill_info  =$waybill->getWaybilInfo(['id'=>$id]);
            foreach ($waybill_info['goods'] as $item){
                $content->row($item['item_title']);
            }

        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id){
        return Admin::content(function (Content $content) use ($id) {

            //运单的详情
            $waybill = \App::make('WaybillRepository');
            $waybill_info  =$waybill->getWaybilInfo(['id'=>$id]);
            $content->header('WaybillNumber:'.$waybill_info['waybill_number']);

            //运单的发货人信息
            $headers = ['Keys', 'Values'];
            $sender = SenderInfo::where('waybill_id',$id)->first();
            $rows = ['SenderName'=>$sender['sender_contactname'],
                'SenderNumber'=>$sender['sender_contactnumber'],
                'SenderAddress'=>$sender['sender_country'].' '.$sender['sender_province'].' '.$sender['sender_city'].' '.$sender['sender_address']
            ];
            $table = new Table($headers, $rows);
            $content->row((new Box('SenderInfo', $table))->style('info')->solid());

            //收件人的信息
            $receiver = ReceiverInfo::where('waybill_id',$id)->first();
            $rows = ['ReceiverName'=>$receiver['receiver_contactname'],
                'ReceiverNumber'=>$receiver['receiver_contactnumber'],
                'ReceiverAddress'=>$receiver['receiver_country'].' '.$receiver['receiver_province'].' '.$receiver['receiver_city'].' '.$receiver['receiver_address']
            ];
            $table = new Table($headers, $rows);
            $content->row((new Box('Receiver', $table))->style('info')->solid());

            //商品详情
            $rows = [];
            $headers = ['GoodsTitle', 'SalePrice', 'PayPrice', 'Weight', 'UnitCode','Quantity','SkuCode'];
            foreach ($waybill_info['goods'] as $key=>$item){
                $rows[$key] = [$item['item_title'],$item['sale_price'],$item['pay_price'],$item['weight'],$item['unit_code'],$item['quantity'],$item['item_skucode']];
            }
            $table = new Table($headers, $rows);
            $content->row((new Box('GoodsLists', $table))->style('info')->solid());

        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Waybill::class, function (Grid $grid) {
            $grid->disableRowSelector();
            $grid->id('ID')->sortable();
            $grid->waybill_number();
            $grid->order_platform();
            $grid->order_amount();
            $grid->pod_amount();
            $grid->is_pod();
            $grid->settlement_status();//结算状态
            $grid->paymant_time();
            $grid->settlement_status();
            $grid->settlement_time();
//            $grid->sender_name();
//            $grid->sender_contactnumber();
//            $grid->sender_country();

//            $grid->created_at();
//            $grid->updated_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Waybill::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->display('waybill_number', 'WaybillNumber');
            //$form->display('goods', 'goods');
            //$form->goods();
            //$goods = $form->display('goods',);
           // var_dump($goods);
           // $form->display('goods','GOODS');
//            $form->display('created_at', 'Created At');
//            $form->display('updated_at', 'Updated At');
        });
    }
}
