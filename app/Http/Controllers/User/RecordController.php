<?php

namespace App\Http\Controllers\User;

use App\Models\Freight;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use App\Seller\Facades\Seller;
use Encore\Admin\Facades\Admin;
use App\Seller\Layout\Content;
use App\Repositories\FreightRepository;
use Illuminate\Support\Facades\Auth;


class RecordController extends Controller
{

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Seller::content(function (Content $content) {

            $content->header('我的记录');
            $content->description('我的记录');

            $content->body($this->grid());

        });
    }

    public function show($id)
    {
        return Seller::content(function (Content $content) use ($id) {

            $content->header('我的记录');
            $content->description('我的记录');


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

            $content->header(__('Transaction'));
            $content->description(__('Transaction'));

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
        return Seller::grid(\App\Models\Record::class, function (Grid $grid) {

            $grid = $this->getMyConditon($grid);

            $grid = \App\Grid\Grid::store($grid);
            $grid->filter(function ($filter) {

                // sql: ... WHERE `user.name` LIKE "%$name%";
                $filter->like('store_name', __('Store Name'));

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
        return Seller::form(Transaction::class, function (Form $form) {
        });
    }
};