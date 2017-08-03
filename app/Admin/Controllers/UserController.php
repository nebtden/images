<?php

namespace App\Admin\Controllers;


use App\Models\Seller;
use App\Models\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;


class UserController extends Controller
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

            $content->header('User');
            $content->description('User');

            $content->body($this->grid());
        });
    }

    public function show($id){
        return Admin::content(function (Content $content) use ($id){

            $content->header('User');
            $content->description('User');


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
            $content->header('User');
            $content->description('User');

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

            $content->header('User');
            $content->description('User');

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
        return Admin::grid(User::class, function (Grid $grid) {
            $grid->disableRowSelector();
            $grid->disableExport();
            $grid->disableCreation();

            $grid->actions(function ($actions){
                $actions->disableDelete();
            });

            $grid->name();
            $grid->balance();
            $grid->overdue();
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


            $form->display('id', 'ID');
            $form->display('name', 'Name');
            $form->select('user_id')->options(function ($id) {
                $user = User::find($id);

                if ($user) {
                    return [$user->id => $user->name];
                }
            })->ajax('/admin/api/users');


        });
    }
}
