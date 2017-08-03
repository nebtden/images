<?php

namespace App\Admin\Controllers;


use App\Models\Client;
use App\Models\Freight;
use App\Models\Seller;
use App\Models\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;


class StoreController extends Controller
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

            $content->header('Seller');
            $content->description('Seller');

            $content->body($this->grid());
        });
    }

    public function show($id){
        return Admin::content(function (Content $content) use ($id){

            $content->header('Seller');
            $content->description('Seller');


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
            $content->header('Seller');
            $content->description('Seller');

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
        return Admin::grid(Seller::class, function (Grid $grid) {

            $grid->disableExport();

            $grid = \App\Grid\Grid::store($grid);

            $grid->filter(function($filter){
                $filter->is('store_id', __('Store_id'));
                $filter->is('store_name', __('Store name'));
                $filter->is('user_name', __('User name'));
                $filter->is('type_id', __('Country'))->select(Client::all()->pluck('name', 'id'));

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
        return Admin::form(Seller::class, function (Form $form) {


            $form->display('store_id', 'Store_id');
            $form->display('store_name', 'Store name');


            $form->select('user_id','User')->options(function ($id) {
                $user = User::find($id);

                if ($user) {
                    return [$user->id => $user->name];
                }
            })->ajax('/admin/api/users');


            $form->saved(function(Form $form) {
                //如果这个店铺有支付情况，则报错，看怎么处理
                DB::beginTransaction();

                $store_id = $form->model()->store_id;
                $type_id  = $form->model()->type_id;
                $freight = Freight::where([
                    'store_id'=>$store_id,
                    'type_id'=>$type_id
                ])->first();
                if($freight){
                    return "can't change the store,because the store have paid!!";
                }


                //user name 同步更新
                DB::update('UPDATE `kili_sellers` s INNER  JOIN users u ON s.user_id = u.id set s.user_name = u.name where s.store_id= ? and s.type_id = ?',[$store_id,$type_id]);

                //更新所有这个店铺的所有者
                DB::update('UPDATE `kili_freight` f INNER  JOIN kili_sellers s ON s.type_id = f.type_id and s.store_id = f.store_id set f.user_id = s.user_id where f.store_id= ? and f.type_id = ?',[$store_id,$type_id]);


                //更新所有这个店铺的所有者
                DB::update('UPDATE `kili_deduction` f INNER  JOIN kili_sellers s ON s.type_id = f.type_id and s.store_id = f.store_id set f.user_id = s.user_id where f.store_id= ? and f.type_id = ?',[$store_id,$type_id]);
                DB::commit();

            });


        });
    }
}
