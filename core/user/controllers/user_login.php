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
                $_SESSION['user_id'] = $user->id;  
                Url::redirect(Config::get('user.login_success_redirect'));
            }
        }

        $fields = array(
            'email' => array(
                'title' => 'Email',
                'type' => 'text'
            ),
            'password' => array(
                'title' => 'Password',
                'type' => 'password'
            ),
            'submit' => array(
                'value' => 'Login',
                'type' => 'submit'
            )
        );

        return Html::form()->build($fields);
    }

    public static function logout()
    {
        unset($_SESSION['user_id']);
        Url::redirect(Config::get('user.logout_redirect'));
    }

}
