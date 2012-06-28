<?php

class Admin_AdminController extends Controller {

    /**
     * Used to redirect to a configured url when visting the admins base URL.
     */
    public static function redirect() {
        Url::redirect(Config::get('admin.default_route'));
    }

    /**
     * Used to run the admin install if it hasn't been created yet.
     */
    public static function install()
    {
        if(Input::post('install'))
        {
            Validate::check('email', array('required', 'email'));
            Validate::check('pass', array('required', 'min:4'));
            Validate::check('pass_conf', array('matches:pass'));

            if(Validate::passed())
            {
                $id = User::user()->insert(array(
                    'email' => Input::post('email'),
                    'pass' => md5(Input::post('pass')),
                    'is_admin' => 1
                ));

                if($id)
                {
                    Message::ok('Installation completed successfully.');
                    Url::redirect('admin/login');
                }
                else
                    Message::error('Error creating admin account, please try again.');
            }
        }
    }

}
