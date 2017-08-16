<?php

namespace App\Http\Controllers\User;

use App\Models\CostRecord;
use App\Models\Freight;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use App\Seller\Facades\Seller;
use Encore\Admin\Facades\Admin;
use App\Seller\Layout\Content;
use App\Repositories\FreightRepository;
use Illuminate\Support\Facades\Auth;


class CostRecordController extends Controller
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
        return Seller::grid(CostRecord::class, function (Grid $grid) {

            $grid->disableRowSelector();
            $user = Auth::user();
            $grid->model()->where('user_id',$user->id);
            $grid->disableExport();
            $grid->disableCreation();
            $grid->disableBatchDeletion();
            $grid->actions(function ($actions){
                $actions->disableDelete();
            });


            $grid->date(__('Date'));
            $grid->column(__('Money'))->display(function () {
                return  '$'.$this->money;
            });


            $grid->filter(function($filter){

                // sql: ... WHERE `user.name` LIKE "%$name%";
                 $filter->between('date', 'date')->datetime();
                //$filter->between('created_at', 'Created Time')->datetime();

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

            $form->display('id',__('ID'));
            $form->display('order_sn',__('Order Sn'));
            $form->display('waybill_number',__('Waybill Number'));
            $form->display('payable_money',__('Payable money'));
            $form->display('receivable_money',__('Receivable money'));
            $form->display('paid_money',__('Paid money'));
            $form->display('real_money',__('Real money'));


        });
    }
}
