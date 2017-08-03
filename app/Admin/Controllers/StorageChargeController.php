<?php

namespace App\Admin\Controllers;

use App\Models\Client;
use App\Models\Freight;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Repositories\FreightRepository;


class StorageChargeController extends Controller
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

            $content->header('Storage Charge Lists');
            $content->description('Storage Charge Lists');

            $content->body($this->grid());
        });
    }

    public function show($id){
        return Admin::content(function (Content $content) use ($id){

            $content->header('header');
            $content->description('description');

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
            $grid->disableRowSelector();
            $grid->model()->orderBy('date', 'desc');
            $grid->model()->where('charge_type',1);
            //$grid->disableExport();
            $grid->exporter('custom-exporter');
            $grid->disableCreation();

            $grid->disableBatchDeletion();
            $grid->disableActions();
//            $grid->actions(function ($actions){
//                $actions->disableDelete();
//            });

//            $grid->id('ID')->sortable();
            $grid->type()->name('country');
            $grid->class_id();
            $grid->goodsclass()->class_name_en();
            $grid->sku_id();
            $grid->store_id();
            $grid->store_name();
            $grid->factor2('Billing factor');
            $grid->quantity();
            $grid->receivable_money('Money($)');
//            $grid->column('Money($)')->display(function () {
//                return  '$'.$this->receivable_money;
//            });

            $grid->column('money_type')->display(function (){
                return FreightRepository::$storage_moneytype[$this->money_type];
            });
            $grid->date();
            $grid->order_sn();
            $grid->column('Is Paid')->display(function () {
                return   $this->is_paid?'Yes':'No';
            });
            $grid->column('pay_time')->display(function () {
                return  $this->pay_time?date('Y-m-d H:i:s',$this->pay_time):'';
            });
            $grid->sku()->goods_name('sku name')->display(function ($name){
                return "<span class='nilo_span' title='".$name."'>$name</span>";
            });
            //$grid->sku()->goods_name('sku name');

            $grid->filter(function($filter){
//                $filter->useModal();
                $filter->like('order_sn', 'order sn');

                $filter->is('type_id', 'Country')->select(Client::all()->pluck('name', 'id'));
                $filter->like('store_name', 'Store name');
                $filter->is('store_id', 'Store id');
                $filter->is('sku_id', 'Sku id');
                $filter->is('is_paid', 'Is Paid')->select(
                    [0=>'No',1=>'Yes']
                );
                $filter->is('money_type', 'money_type')->select(
                    FreightRepository::$storage_moneytype);
                $filter->between('date', 'date')->datetime();
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



            $form->display('store_name', 'Store name');
            $form->display('order_sn', 'order sn');
            $form->number('receivable_money', 'Receivable Money');
            $form->hidden('admin_user_id');
            $form->saving(function(Form $form) {

                $user = Admin::user();

                $form->admin_user_id = $user->id;


                if($form->model()->paid_money >0  ){
                    throw new \Exception('aready paid,can\'t changed receivable money!');
                }


            });

        });
    }
}
