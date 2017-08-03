<?php

namespace App\Admin\Controllers;

use App\Models\Client;
use App\Models\Deduction;
use App\Models\Freight;
use App\Repositories\FreightRepository;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;


class DeductionController extends Controller
{

    /**
     * Index interface.
     *
     * @return Content
     */
//    public function index()
//    {
//        return Admin::content(function (Content $content) {
//
//            $content->header('Deduction');
//            $content->description('Deduction');
//
//            $content->body($this->grid());
//
//        });
//    }

    public function show($id){
        return Admin::content(function (Content $content) use ($id){

            $content->header('Statistics');
            $content->description('Statistics');

        });
    }

    public function day(){
        //return 1111;
        return Admin::content(function (Content $content) {

            $content->header('Statistics');
            $content->description('Statistics');

            $content->body($this->grid(1));

        });
    }

    public function week(){
        return Admin::content(function (Content $content) {

            $content->header('Statistics');
            $content->description('Statistics');

            $content->body($this->grid(2));

        });
    }

    public function month(){
        return Admin::content(function (Content $content) {

            $content->header('Statistics');
            $content->description('Statistics');

            $content->body($this->grid(3));

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

            $content->header('Statistics');
            $content->description('Statistics');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     * @param  $type date_type 类型
     * @return Grid
     */
    protected function grid($type)
    {
        return Admin::grid(Deduction::class, function (Grid $grid) use ($type) {


            $grid->model()->orderBy('date', 'desc');
            $grid->model()->where('date_type',$type);
            $grid->disableExport();
            $grid->disableCreation();

            $grid->disableRowSelector();
            $grid->disableBatchDeletion();
            $grid->disableActions();


            $grid->type()->name('country');
            if($type==1){
                $grid->date();
            }
            if($type==2){
                $grid->week();
            }
            if($type==3){
                $grid->month();
            }

            $grid->store_id();
            $grid->store_name();
            $grid->column('amount')->display(function () {
                return  '$'.$this->amount;
            });
            $grid->column('charge_type')->display(function (){
                return FreightRepository::$chargetype[$this->charge_type];
            });
            $grid->column('money_type')->display(function (){
                if($this->charge_type==0){
                    return FreightRepository::$transit_moneytype[$this->money_type];
                }
                if($this->charge_type==1){
                    return FreightRepository::$storage_moneytype[$this->money_type];
                }
                if($this->charge_type==2){
                    return FreightRepository::$delivery_moneytype[$this->money_type];
                }

            });


            $grid->filter(function($filter) use ($type){

                $filter->is('type_id', 'Country')->select(Client::all()->pluck('name', 'id'));
                $filter->is('charge_type', 'Charge type')->select(
                    FreightRepository::$chargetype
                );
                $filter->like('store_name', 'Store name');

                if($type==1){
                    $filter->between('date', 'Date')->datetime();
                }


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
            $form->display('payable_money', 'Payable money');
            $form->display('receivable_money', 'Receivable money');
            $form->display('paid_money', 'Paid money');
            $form->display('real_money', 'Real money');


        });
    }
}
