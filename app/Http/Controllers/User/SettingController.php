<?php

namespace App\Http\Controllers\User;


use Encore\Admin\Form;
use App\Seller\Facades\Seller;
use Encore\Admin\Facades\Admin;
use App\Seller\Layout\Content;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;


class SettingController extends Controller
{
    //use ModelForm;
//    use ResetsPasswords;
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Seller::content(function (Content $content) {

            $content->header(__('Change Password'));
            $content->description(__('Change Password'));

            $user = Auth::user();
            $content->body($this->form()->edit($user->id));

        });
    }

    public function show($id){
        return Seller::content(function (Content $content) use ($id){

            $content->header(__('Change Password'));
            $content->description(__('Change Password'));


        });
    }


    public function update($id)
    {
        return $this->form()->update($id);
    }

/*    public function update(Request $request){

        $password  = $request->input('password');
        if($password){
            $user = Auth::user();
            //@simon.zhang  需要验证位数等。。进行加密
            $user->password = bcrypt($password);
            $user->update();
        }

        return redirect('/seller/setting');

    }*/


    public function edit($id)
    {
        return Seller::content(function (Content $content) use ($id) {

            $content->header(__('Change Password'));
            $content->description(__('Change Password'));

            $content->body($this->form()->edit($id));
        });
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {

        return Admin::form(Auth::user(), function (Form $form) {
            $user = Auth::user();
            if(!$user->is_reset_password){
                $form->html("<span style='color: red'>".__('Please change your password when first login in!')."<span>");
            }

            //检测id是否为自己id  @todo
            $currentRoute = Route::current();//获取当前地址信息
            $params = $currentRoute->parameters();//获取参数

            $id = $params['setting'];
            if($id!=$user->id){
                return false;
            }

            $form->display('name', __('Name'));
            $form->password('old_password',__('Old Password'))->rules('required');;
            $form->password('password',__('Password'))->rules('required|length:6,20');

            $form->password('password_again',__('Password Again'))->rules('required|same:password');;

            $form->hidden('is_reset_password');


            $form->saving(function(Form $form) {


                if (!Hash::check($form->old_password, $form->model()->password)) {
                    throw  new \Exception(__('Old Password Error!'));
                }
                if($form->password){
                    $form->password = bcrypt($form->password);
                }
                $form->is_reset_password = 1;

            });

            $form->saved(function(){
                $request = App()->request;

                $request->session()->flush();

                $request->session()->regenerate();

                return redirect('/login');
            });

        });
    }
}
