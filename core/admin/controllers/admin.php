<?php

class Admin_AdminController extends Controller {

    /**
     * Used to redirect to a configured url when visting the admins base URL.
     */
    public static function redirect() {
        Url::redirect(Config::get('admin.default_route'));
    }

    /**
     * TODO
     */
    public static function dashboard()
    {
        return array(
            'title' => 'Dashboard',
            'content' => '<p>Dashboard is under development.</p>'
        );
    }

    /**
     * Used to run the admin install if it hasn't been created yet.
     */
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
