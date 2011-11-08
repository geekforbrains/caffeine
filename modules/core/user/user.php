<?php

class User extends Module {

    private static $_permissions = array();

    public static function load($permissions) {
        self::$_permissions = array_merge($permissions, self::$_permissions);
    }

    public static function login($email, $pass)
    {
        $user = User::user()
            ->where('email', '=', $email)
            ->andWhere('pass', '=', md5($pass))->first();

        if($user)
        {
            $_SESSION['user']['id'] = $user->id;  
            Url::redirect(Config::get('user.login_success_redirect'));
        }

        return false;
    }

    public static function logout() {
        unset($_SESSION['user']);
    }

    public static function createAccount()
    {

    }

    public static function create()
    {

    }

}
