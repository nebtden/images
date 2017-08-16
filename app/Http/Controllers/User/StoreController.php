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


class StoreController extends Controller
{

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Seller::content(function (Content $content) {

            $content->header(__('Stores'));
            $content->description(__('Stores List'));

            $content->body($this->grid());

        });
    }

    public function show($id){
        return Seller::content(function (Content $content) use ($id){

            $content->header(__('Stores'));
            $content->description(__('Stores'));


        });
    }




    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Seller::grid(\App\Models\Seller::class, function (Grid $grid) {

            $grid = $this->getMyConditon($grid);

            $grid = \App\Grid\Grid::store($grid);
            $grid->filter(function($filter){

                // sql: ... WHERE `user.name` LIKE "%$name%";
                $filter->like('store_name',__('Store Name'));

            });

        });
    }

}
