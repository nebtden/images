<?php

namespace App\Admin\Controllers;


use App\Models\Settings;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\FreightRepository;
use App\Repositories\SettingsRepository;
use App\Repositories\TransactionRepository;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\MessageBag;


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
        return Admin::content(function (Content $content) {

            $content->header('Transaction Lists');
            $content->description('Transaction Lists');

            $content->body($this->grid());

        });
    }

    public function show($id){
        return Admin::content(function (Content $content) use ($id){

            $content->header('header');
            $content->description('description');

            $content->body($this->form()->view($id));

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

            $content->header('Transaction');
            $content->description('Transaction');

            $content->body($this->form());
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
            $content->header('header');
            $content->description('description');

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
        return Admin::grid(Transaction::class, function (Grid $grid) {

            $grid->model()->orderBy('id', 'desc');
            $grid->disableRowSelector();
            //$grid->disableExport();
            $grid->exporter('custom-exporter');
            $grid->disableCreation();

            $grid->disableBatchDeletion();

            $grid->actions(function ($actions) {
                $actions->disableDelete();
            });


            $grid->bank('Bank Name');
            $grid->real_name('Transfer people');
            $grid->bank_account('Bank Account');
            $grid->money();
            $grid->column('money type')->display(function (){
                return SettingsRepository::getMoneyType()[$this->money_type];
            });
            $grid->trans_id();
            $grid->column('status')->display(function (){
                return TransactionRepository::$status[$this->status];
            });
            $grid->user_name('User Name');
            $grid->created_at('created time');
            $grid->admin_user()->name('review user');
            $grid->review_at('review time');

            $grid->filter(function($filter){

                $filter->like('bank', 'bank name');

                $filter->is('user_name','User name');

                $filter->is('status')->select(
                    TransactionRepository::$status
                );
               $filter->between('created_at', 'approve date')->datetime();
               // $filter->between('updated_at', 'create date')->datetime();
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
        return Admin::form(Transaction::class, function (Form $form) {

            $currentRoute = Route::current();//获取当前地址信息
            $params = $currentRoute->parameters();//获取参数
//           dd($currentRoute);
            $id = $params['transaction'];
            $transaction = Transaction::find($id);
            if($transaction->status!=0){
                $form->disableReset();
                $form->disableSubmit();
            }


           // $seller_user = User::findOrFail($form->model()->user_id);
            $form->display('user.name', 'user name');
            $form->display('bank', 'Bank Name');
            $form->display('bank_account', 'Bank Account');
            $form->display('real_name', 'Transfer people');
            $money_type_list = SettingsRepository::getMoneyType();

            $form->display('money_types', 'Money Type')->default($money_type_list[$transaction['money_type']]);
            $form->display('money', 'Money');
            $form->display('trans_id', 'trans_id');


            $form->radio('status')->values(['1' => 'Approved
', '2'=> 'Refused'])->rules('required');
            $form->image('img');

            $form->hidden('admin_user_id');
            $form->hidden('user_id');
            $form->hidden('review_at');
            $form->ignore(['img','user_name','money_types']);

            $form->textarea('remark', 'Remark')->rules('required');

            $form->saving(function(Form $form) {

                if($form->status==0){
                    $validationMessages = [];
                    $validationMessages['status'][] = 'status must have select';
                    $messageBag = new MessageBag($validationMessages);
                    return back()->withInput()->withErrors($messageBag);
                }

                $user = Admin::user();

                $form->admin_user_id = $user->id;
                $form->review_at = date('Y-m-d H:i:s');
                if($form->model()->status ==1  ){
                    throw new \Exception('aready passed,can\'t changed status');
                }


                //如果审核正确，添加金额到用户列表中
                if($form->status==1){
                    DB::beginTransaction();
                    $seller_user = User::findOrFail($form->user_id);

                    //汇率计算
                    $money_type = $form->model()->money_type;

                    $money = Settings::find($money_type);
                    $balance = ($form->model()->money)/$money['value'];

                    $seller_user->balance = $seller_user->balance + $balance;
                    $seller_user->real_balance = $seller_user->real_balance + $balance;
                    $seller_user->update();

                    //更新deduction
                    //更新freight
                    //TransactionRepository::calMoney($seller_user);

                    DB::commit();
                }


            });

        });
    }
}
