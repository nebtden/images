<?php
/**
 * Created by PhpStorm.
 * User: zhanqian
 * Date: 2017/6/12
 * Time: 11:49
 */
namespace App\Seller\Layout;

use Encore\Admin\Layout\Content as AdminContent;

class Content extends AdminContent{

    public function render()
    {
        $items = [
            'header'      => $this->header,
            'description' => $this->description,
            'content'     => $this->build(),
        ];

        return view('seller.content', $items)->render();
    }
}