<?php

namespace App\Http\Controllers\User;

use App\Models\Client;
use App\Models\Deduction;
use App\Models\Freight;
use App\Repositories\FreightRepository;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use App\Seller\Facades\Seller;

use App\Seller\Layout\Content;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;


class DeductionController extends Controller
{

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {

        return Seller::content(function (Content $content) {

            $content->header(__('Statistics'));
            $content->description(__('Statistics'));

            $content->body($this->grid());

        });
    }


    public function day(){

        return Seller::content(function (Content $content) {

            $content->header(__('Statistics'));
            $content->description(__('Statistics'));

            $content->body($this->grid(1));

        });
    }

    public function week(){
        return Seller::content(function (Content $content) {

            $content->header(__('Statistics'));
            $content->description(__('Statistics'));

            $content->body($this->grid(2));

        });
    }

    public function month(){
        return Seller::content(function (Content $content) {

            $content->header(__('Statistics'));
            $content->description(__('Statistics'));

            $content->body($this->grid(3));

        });
    }


    public function show($id){
        return Seller::content(function (Content $content) use ($id){

            $content->header(__('Statistics'));
            $content->description(__('Statistics'));

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

            $content->header(__('Statistics'));
            $content->description(__('Statistics'));
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     * @param $type 类型
     * @return Grid
     */
    protected function grid($type='')
    {
        return Seller::grid(Deduction::class, function (Grid $grid) use ($type){

            $user = Auth::user();
            $grid->model()->where('user_id',$user->id);
            $grid->model()->where('date_type',$type);
            $grid->model()->orderBy('date', 'desc');
            $grid->disableExport();
            $grid->disableCreation();

            $grid->disableRowSelector();
            $grid->disableBatchDeletion();
            $grid->disableActions();


            $grid->type()->name(__('Country'));
            if($type==1){
                $grid->date(__('Date'));
            }
            if($type==2){
                $grid->week(__('Week'));
            }
            if($type==3){
                $grid->month(__('Month'));
            }
            $grid->store_name(__('Store Name'));
            $grid->column(__('Amount'))->display(function () {
                return  '$'.$this->amount;
            });
            $grid->column(__('Charge Type'))->display(function (){
                return FreightRepository::$chargetype[$this->charge_type];
            });
            $grid->column(__('Money Type'))->display(function (){
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
                $filter->is('type_id', __('Country'))->select(Client::all()->pluck('name', 'id'));
                $filter->is('charge_type', __('Charge Type'))->select(
                    FreightRepository::$chargetype
                );

                $filter->like('store_name', __('Store Name'));

                if($type==1){
                    $filter->between('date', __('Date'))->datetime();
                }
            });

        });
    }


}
