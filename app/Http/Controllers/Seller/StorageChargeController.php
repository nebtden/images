<?php

namespace App\Http\Controllers\Seller;

use App\Models\Client;
use App\Models\Freight;
use App\Models\GoodsClass;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use App\Seller\Facades\Seller;
use Encore\Admin\Facades\Admin;
use App\Seller\Layout\Content;
use App\Repositories\FreightRepository;


class StorageChargeController extends Controller
{

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Seller::content(function (Content $content) {

            $content->header(__('Storeage Charge Lists'));
            $content->description(__('Storeage Charge Lists'));

            $content->body($this->grid());

        });
    }


    public function show($id){
        return Seller::content(function (Content $content) use ($id){

            $content->header(__('Storeage Charge'));
            $content->description(__('Storeage Charge'));
        });
    }


    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Seller::content(function (Content $content) {

            $content->header(__('Storeage Charge'));
            $content->description(__('Storeage Charge'));

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
        return Seller::grid(Freight::class, function (Grid $grid) {


            $grid = $this->getMyConditon($grid);
            $grid->model()->where('charge_type',1);
            $grid->model()->orderBy('date', 'desc');
//            $grid->disableExport();
            $grid->exporter('custom-exporter');
            $grid->disableCreation();
            $grid->disableBatchDeletion();
            $grid->disableRowSelector();
            $grid->disableActions();



            $grid->type()->name(__('Country'));
            $grid->goodsclass()->class_name_en(__('Class Name'));
            $grid->sku_id(__('Sku Id'));
            $grid->store_name(__('Store Name'));
            $grid->factor2(__('Billing factor'));
            $grid->quantity(__('Quantity'));

            $grid->receivable_money(__('Money($)'));
            $grid->date(__('Date'));
            $grid->order_sn(__('Order Sn'));
            $grid->column(__('Is Paid'))->display(function () {
                return   $this->is_paid?'Yes':'No';
            });
//            $grid->column('pay_time')->display(function () {
//                return  $this->pay_time?date('Y-m-d H:i:s',$this->pay_time):'';
//            });
            $grid->sku()->goods_name(__('Sku Name'))->display(function ($name){
                return "<span class='nilo_span' title='".$name."'>$name</span>";
            });
//            $grid->goods_name(function () {
//                return "<span style='color: red;'>$this->sku()->goods_name()</span>";
//            });

            $grid->filter(function($filter){

                // sql: ... WHERE `user.name` LIKE "%$name%";
                $filter->like('order_sn', __('Order Sn'));
                $filter->is('type_id', __('Country'))->select(Client::all()->pluck('name', 'id'));
;
                $filter->is('is_paid', __('Is Paid'))->select(
                    [0=>'No',1=>'Yes']
                );
                $filter->like('store_name', __('Store Name'));
                $filter->is('sku_id', __('Sku Id'));
                $filter->is('money_type', __('Money type'))->select(
                     FreightRepository::$storage_moneytype);
                $filter->between('date', __('Date'))->datetime();
            });



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
            $form->currency('payable_money', 'Payable money');
            $form->currency('receivable_money', 'Receivable money');
            $form->currency('paid_money', 'Paid money');
            $form->currency('real_money', 'Real money');



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
