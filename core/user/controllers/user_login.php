<?php 

class User_User_LoginController extends Controller {

    public static function login()
    {
        if($_POST)
        {
            $user = User::user()
                ->where('email', '=', $_POST['email'])
                ->andWhere('pass', '=', md5($_POST['password']))->first();

            if($user)
            {
                $_SESSION[Config::get('user.session_key')] = $user->id;
                Url::redirect('admin');
            }
            else
                Message::error('Invalid login details.');
        }
    }

    public static function logout()
    {
        unset($_SESSION[Config::get('user.session_key')]);
        Url::redirect('admin/logout');
    }

}
