<?php

namespace App\Admin\Controllers;

use App\Models\Freight;
use App\Models\GoodsClass;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Repositories\FreightRepository;


class GoodsClassController extends Controller
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

            $content->header('Goods Class');
            $content->description('Goods Class');

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
        return Admin::grid(GoodsClass::class, function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');
            $grid->disableExport();
            $grid->disableCreation();
            $grid->disableActions();
            $grid->disableBatchDeletion();
//             $grid->allowDelete();
            $grid->actions(function ($actions){
                $actions->disableDelete();
            });


//            $grid->id();
            $grid->class_name_en();
            $grid->class_name_cn();


            $grid->column('price')->display(function () {
                return  '$'.$this->price;
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
        return Admin::form(GoodsClass::class, function (Form $form) {


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
