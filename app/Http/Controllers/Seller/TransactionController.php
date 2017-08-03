<?php

namespace App\Http\Controllers\Seller;


use App\Models\Settings;
use App\Models\Transaction;
use App\Repositories\SettingsRepository;
use App\Repositories\TransactionRepository;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use App\Seller\Facades\Seller;
use App\Seller\Layout\Content;
use Illuminate\Support\Facades\Auth;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Widgets\Box;
use Intervention\Image\ImageManagerStatic as Image;

class TransactionController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Seller::content(function (Content $content) {

            $content->header(__('Transaction Lists'));
            $content->description(__('Transaction Lists'));

            $content->body($this->grid());

        });
    }

    public function show($id){
        return Seller::content(function (Content $content) use ($id){

            $content->header(__('Transaction'));
            $content->description(__('Transaction'));


            $info = Transaction::where(['id'=>$id])->first();
            $content->row($info['id']);
            $content->row($info['trans_id']);
            $content->row(new Box('title', 'xxxx'));
//            $content->row($info['']);
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
        return Seller::grid(Transaction::class, function (Grid $grid) {

            $grid->disableRowSelector();
            $user = Auth::user();
            $grid->model()->where('user_id', $user->id);
            //$grid->disableExport();
            $grid->exporter('custom-exporter');
            $grid->disableRowSelector();

            $grid->model()->orderBy('id', 'desc');
            $grid->disableBatchDeletion();
            $grid->disableActions();
            $grid->bank(__('Bank Name'));
            $grid->real_name(__('Transfer people'));
            $grid->bank_account(__('Bank Account'));
            $grid->money(__('Money'));
            $grid->column(__('Money Type'))->display(function (){
                return SettingsRepository::getMoneyType()[$this->money_type];
            });
            $grid->column(__('Status'))->display(function (){
                return TransactionRepository::$status[$this->status];
            });
            $grid->trans_id(__('Trans Id'));
            $grid->column(__('Created user'))->display(function (){
                $user = Auth::user();
                return $user->name;
            });
            $grid->created_at(__('Created Time'));
            $grid->admin_user()->name(__('Review User'));
            $grid->review_at(__('Review Time'));
            $grid->remark(__('Remark'));
            $grid->filter(function($filter){
                $filter->like('bank', __('Bank'));
                $filter->between('created_at', __('Date'))->datetime();
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

            $user = Auth::user();
            $form->display('id', 'ID');
            $form->display('user_name', __('User Name'))->value($user->name);
            $form->text('bank', __('Bank Name'))->rules('required');
            $form->text('real_name', __('Transfer people'))->rules('required');
            $form->text('bank_account', __('Bank Account'))->rules('required');

            $money_type_list = SettingsRepository::getMoneyType();
            $form->select('money_type',__('Currency'))->options($money_type_list);
            $form->number('money', __('Amount'))->rules('required|gtz');
            $form->text('trans_id', __('Trans Id'))->rules('required|codeunique');
            // 剪裁图片

//            $form->image('img');
            $form->ignore(['user_name']);
            $form->image('img',__('Img'))->rules('max:2500')->name(function ($file) {
                $user = Auth::user();
                $imgfile = $user->id.'/'.time().'.';
//                $file->resize(80,60);
                return $imgfile.$file->guessExtension();
            });
           // $form->image('img')->flip('v');
            $form->hidden('user_id');
            $form->hidden('user_name');
            $form->hidden('usd');



            $form->saving(function(Form $form) {
                //$this->->crop(80, 60)->save($dir,60)
                $user = Auth::user();
                $form->user_id = $user->id;
                $form->user_name = $user->name;

                //money_type
                $money_type = $form->money_type;
                $money = Settings::find($money_type);
                $usd = ($form->money)/$money['value'];

                $form->usd = $usd;
            });

            $form->saved(function(Form $form){
                $img = $form->model()->img;
                if($img){
                    // open an image file
                    $img = Image::make(public_path().'/transaction/'.$img);
                    $height = $img->height();
                    $width = $img->width();
                    if($height+$width>1400){
                        $img->resize($width/2, $height/2);
                        $img->save();
                    }
                }

            });

        });
    }
}
