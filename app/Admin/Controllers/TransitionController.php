<?php

namespace App\Admin\Controllers;


use App\Models\Freight;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\FreightRepository;
use App\Repositories\TransactionRepository;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;


class TransitionController extends Controller
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

            $content->header('Transition Lists');
            $content->description('Transition Lists');

            $content->body($this->grid());
            //$data = $this->grid()->getFilter()->execute();

            //$content->body(\GuzzleHttp\json_encode($data));

        });
    }

    public function show($id){
        return Admin::content(function (Content $content) use ($id){

            $content->header('header');
            $content->description('description');



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

            $content->header('Transition');
            $content->description('Transition');

            $content->body($this->form());
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
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Freight::class, function (Grid $grid) {
            $grid->disableRowSelector();
            $grid->model()->where('charge_type', 0);  //0 表示中转仓
            $grid->model()->orderBy('date', 'desc');

            $grid->disableCreation();
            $grid->exporter('data-exporter');
//            $grid->disableExport();
            $grid->disableBatchDeletion();
            $grid->disableActions();
//            $grid->actions(function ($actions){
//                $actions->disableDelete();
//            });

//            $grid->id('ID')->sortable();
            $grid->type()->name('country');

            $grid->waybill_number();
            $grid->order_sn();

            $grid->column('money_type')->display(function (){
                return FreightRepository::$transit_moneytype[$this->money_type];
            });

            $grid->factor8('from country');
            $grid->factor9('from city');
            $grid->factor1('to country');


            $grid->column('length*width*height')->display(function (){
                return (int)($this->factor3).'*'.(int)($this->factor4).'*'.(int)($this->factor5);
            });
            $grid->factor6('seller weight');
            $grid->weight();

            $grid->receivable_money('Money($)');
            $grid->column('Is Paid')->display(function () {
                return   $this->is_paid?'Yes':'No';
            });

            $grid->store_id();
            $grid->store_name();
            $grid->date();
            $grid->filter(function($filter){
                $filter->like('order_sn', 'Order sn');
                $filter->like('store_name', 'Store Name');
                $filter->is('store_id', 'Store Id');
                $filter->is('is_paid', 'Is Paid')->select(
                    [0=>'No',1=>'Yes']
                );
                $filter->is('money_type', 'money_type')->select(
                    FreightRepository::$transit_moneytype);
                $filter->between('date', '收货时间')->datetime();
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
                $form->receivable_money =  round($form->receivable_money ,3);

            });
        });
    }
}
