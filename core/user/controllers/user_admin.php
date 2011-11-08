<?php

class User_User_AdminController extends Controller {

    public static function login()
    {
        if($_POST)
        {
            $user = User::user()
                ->where('email', '=', $_POST['username'])
                ->andWhere('pass', '=', md5($_POST['password']))->first();

            if($user)
            {
                $_SESSION['user']['id'] = $user->id;  
                Url::redirect(Config::get('user.login_success_redirect'));
            }
        }

        $fields = array(
            'username' => array(
                'title' => 'Username',
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
        unset($_SESSION['user']);
        Url::redirect(Config::get('user.logout_redirect'));
    }

    public static function manage()
    {
        $headers = array('Username');
        $rows = array(array('<em>No users.</em>'));

        return Html::table()->build($headers, $rows);
    }

    public static function create()
    {
        if($_POST)
        {
            Dev::debug('user', print_r($_POST, true));
        }

        $fields = array(
            'username' => array(
                'title' => 'Username',
                'type' => 'text'
            ),
            'password' => array(
                'title' => 'Password',
                'type' => 'password'
            ),
            'confirm_password' => array(
                'title' => 'Confirm Password',
                'type' => 'password'
            ),
            'submit' => array(
                'value' => 'Create User',
                'type' => 'submit'
            )
        );

        return Html::form()->build($fields);
    }

    public static function edit($id)
    {

    }

    public static function delete($id)
    {

    }

}
