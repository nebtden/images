<?php

namespace App\Http\Controllers\Seller;

use App\Models\Freight;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use App\Seller\Facades\Seller;
use Encore\Admin\Facades\Admin;
use App\Seller\Layout\Content;
use App\Repositories\FreightRepository;
use Illuminate\Support\Facades\Auth;


class TransitionController extends Controller
{

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Seller::content(function (Content $content) {

            $content->header(__('Transit Warehouse Fee'));
            $content->description(__('Transit Warehouse Fee'));

            $content->body($this->grid());

        });
    }

    public function show($id){
        return Seller::content(function (Content $content) use ($id){

            $content->header(__('Transit Warehouse Fee'));
            $content->description(__('Transit Warehouse Fee'));


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

            $content->header(__('Transit Warehouse Fee'));
            $content->description(__('Transit Warehouse Fee'));

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
            $grid->model()->where('charge_type', 0);  //0 表示中转
            $grid->disableRowSelector();
            $grid->exporter('custom-exporter');

            $grid->disableCreation();

            $grid->disableBatchDeletion();
//             $grid->allowDelete();
            $grid->disableActions();

//            $grid->id('ID')->sortable();
            $grid->waybill_number(__('Waybill Number'));
            $grid->order_sn(__('Order Sn'));

            $grid->column(__('Money Type'))->display(function (){
                return FreightRepository::$transit_moneytype[$this->money_type];
            });

            $grid->factor8(__('From Country'));
            $grid->factor9(__('From City'));
            $grid->factor1(__('To Country'));


            $grid->column(__('Volume'))->display(function (){
                return (int)($this->factor3).'*'.(int)($this->factor4).'*'.(int)($this->factor5);
            });

            $grid->factor6(__('Seller Weight'));
            $grid->weight(__('Weight'));

            $grid->receivable_money(__('Money($)'));
            $grid->column(__('Is Paid'))->display(function () {
                return   $this->is_paid?'Yes':'No';
            });
//            $grid->column('paid_money')->display(function () {
//                return  '$'.sprintf('%.3f', $this->paid_money);
//            });

            $grid->store_id(__('Store Id'));
            $grid->store_name(__('Store Name'));
            $grid->date(__('Date'));
            $grid->filter(function($filter){
                $filter->like('order_sn', __('Order Sn'));
                $filter->like('store_name', __('Store Name'));
                $filter->is('store_id', __('Store Id'));
                $filter->is('is_paid', __('Is Paid'))->select(
                    [0=>'No',1=>'Yes']
                );
                $filter->is('money_type', __('Money Type'))->select(
                    FreightRepository::$transit_moneytype);
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
            $form->display('payable_money', 'Payable money');
            $form->display('receivable_money', 'Receivable money');
            $form->display('paid_money', 'Paid money');
            $form->display('real_money', 'Real money');


        });
    }
}
