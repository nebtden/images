<?php

namespace App\Admin\Controllers;

use App\Models\Freight;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Repositories\FreightRepository;


class FreightController extends Controller
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

            $content->header('Freight Lists');
            $content->description('Freight Lists');

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
            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
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
        return Admin::grid(Freight::class, function (Grid $grid) {

            $grid->disableExport();
            $grid->disableCreation();
            $grid->disableBatchDeletion();
//             $grid->allowDelete();
            $grid->actions(function ($actions){
                $actions->disableDelete();
            });

//            $grid->id('ID')->sortable();
            $grid->order_sn();
            $grid->waybill_number();
            $grid->goodsclass()->class_name_en();
            $grid->column('charge_type')->display(function (){
                 return FreightRepository::$chargetype[$this->charge_type];
            });
            $grid->column('money_type')->display(function (){
                return FreightRepository::$delivery_moneytype[$this->money_type];
            });
            $grid->waybill_number();

            $grid->column('payable_money')->display(function () {
                return  '$'.$this->payable_money;
            });
            $grid->column('receivable_money')->display(function () {
                return  '$'.$this->receivable_money;
            });
            $grid->column('paid_money')->display(function () {
                return  '$'.$this->paid_money;
            });
            $grid->column('real_money')->display(function () {
                return  '$'.$this->real_money;
            });


            $grid->store_id();
            $grid->city();
            $grid->weight();


            $grid->column('add_time')->display(function () {
                return  $this->add_time?date('Y-m-d H:i:s',$this->add_time):'';
            });
            $grid->column('pay_time')->display(function () {
                return  $this->pay_time?date('Y-m-d H:i:s',$this->pay_time):'';
            });
            $grid->filter(function($filter){

                // sql: ... WHERE `user.name` LIKE "%$name%";
                $filter->like('order_sn', 'order sn');
                $filter->like('waybill_number', 'waybill number');
                $filter->like('store_id', 'seller id');

            });
//            $grid->actions('edit|delete');
            $grid->rows(function($row){


                    //$row->actions('edit');



            });


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
        return Admin::form(Freight::class, function (Form $form) {


            $form->display('id', 'ID');
            $form->display('order_sn', 'Order Sn');
            $form->display('waybill_number', 'Waybill Number');
            $form->display('payable_money', 'Payable money');
            $form->display('receivable_money', 'Receivable money');
            $form->display('paid_money', 'Paid money');
            $form->display('real_money', 'Real money');

            //涉及更改的，

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
