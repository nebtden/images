<?php


/**
 * @simon.zhang
 * 第一期暂时不上
 */
namespace App\Http\Controllers\Seller;

use App\Models\Freight;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use App\Seller\Facades\Seller;
use Encore\Admin\Facades\Admin;
use App\Seller\Layout\Content;
use App\Repositories\FreightRepository;

class FreightController extends Controller
{

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Seller::content(function (Content $content) {

            $content->header('Freight Lists');
            $content->description('Freight Lists');

            $content->body($this->grid());

        });
    }

    public function show($id){
        return Seller::content(function (Content $content) use ($id){

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
     * Create interface.
     *
     * @return Content
     */


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Seller::grid(Freight::class, function (Grid $grid) {

            $grid = $this->getMyConditon($grid);
            $grid->model()->where('charge_type', 2);  //0 表示中转
            $grid->model()->orderBy('id', 'desc');

//            $grid->disableExport();
            $grid->exporter('custom-exporter');
            $grid->disableCreation();
            $grid->disableBatchDeletion();
//             $grid->allowDelete();
            $grid->actions(function ($actions){
                $actions->disableDelete();
            });


            $grid->order_sn();
            $grid->waybill_number();

            $grid->column('money_type')->display(function (){
                return FreightRepository::$moneytype[$this->money_type];
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
