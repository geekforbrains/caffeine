<?php

class Admin_AdminController extends Controller {

    public static function redirect() {
        Url::redirect(Config::get('admin.default_route'));
    }

    public static function install()
    {
        if($_POST)
        {
            Validate::check('email', array('email'));
            Validate::check('password', array('required'));
            Validate::check('conf_password', array('matches:password'));
        
            if(Validate::passed())
            {
                $userId = User::user()->insert(array(
                    'email' => $_POST['email'],
                    'pass' => md5($_POST['password']),
                    'is_admin' => 1
                ));

                if($userId)
                {
                    Message::ok('Admin install complete.');
                    Url::redirect('admin/login');
                }
                else
                    Message::error('Error creating admin account. Please try again.');
            }
        }
    }

}
