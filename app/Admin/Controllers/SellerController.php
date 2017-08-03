<?php

namespace App\Admin\Controllers;


use App\Models\Client;
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


class SellerController extends Controller
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

            $content->body($this->form()->edit($id));
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
        return Admin::grid(User::class, function (Grid $grid) {

            $grid->disableRowSelector();
            $grid->disableExport();
            $grid->disableCreation();

            $grid->actions(function ($actions){
                $actions->disableDelete();
            });

            $grid->name();
            $grid->balance('balance');
            $grid->store()->value(function ($sellers) {

                $sellers = array_map(function ($seller) {
                    return "<span class='label label-success'> {$seller['store_name']}</span>";
                }, $sellers);

                return join('&nbsp;', $sellers);
            });

            $grid->filter(function($filter){

                $filter->like('name', 'name');


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

//            $currentRoute = Route::current();//获取当前地址信息
//            $params = $currentRoute->parameters();//获取参数
//
//            $id = $params['seller'];
//            $user_id = Seller::find($id)->user_id;
//            $user = User::find($user_id);
//
//            $form->display('store_id', 'Store_id');
//            $form->display('store_name', 'Store name');
//
//
//            $form->select('user_id','User')->options(function ($id) {
//                $user = User::find($id);
//
//                if ($user) {
//                    return [$user->id => $user->name];
//                }
//            })->ajax('/admin/api/users');
//
//
//            $form->saved(function(Form $form) {
//                $store_id = $form->model()->store_id;
//                $type_id  = $form->model()->type_id;
//
//                //更新所有这个店铺的所有者
//                DB::update('UPDATE `kili_freight` f INNER  JOIN kili_sellers s ON s.type_id = f.type_id and s.store_id = f.store_id set f.user_id = s.user_id where f.store_id= ? and f.type_id = ?',[$store_id,$type_id]);
//
//
//                //更新所有这个店铺的所有者
//                DB::update('UPDATE `kili_deduction` f INNER  JOIN kili_sellers s ON s.type_id = f.type_id and s.store_id = f.store_id set f.user_id = s.user_id where f.store_id= ? and f.type_id = ?',[$store_id,$type_id]);
//
//
//            });


        });
    }
}
