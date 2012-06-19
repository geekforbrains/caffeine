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
        if(Input::post('install') && Html::form()->validate())
        {
            $post = Input::clean($_POST);

            $id = User::user()->insert(array(
                'email' => $post['email'],
                'pass' => md5($post['password']),
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

        $form = Html::form();

        $form->addText('email', array(
            'validate' => array('required', 'email'),
            'attributes' => array(
                'placeholder' => 'Email:',
                'class' => 'span12'
            )
        ));

        $form->addPassword('password', array(
            'validate' => array('required', 'min:4'),
            'attributes' => array(
                'placeholder' => 'Password:',
                'class' => 'span12'
            )
        ));

        $form->addPassword('password', array(
            'validate' => array('required', 'matches:password'),
            'attributes' => array(
                'placeholder' => 'Confirm Password:',
                'class' => 'span12'
            )
        ));

        $form->addSubmit('install', 'Install');

        View::data('form', $form->render());
    }

}
