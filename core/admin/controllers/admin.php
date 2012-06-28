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

    public static function login()
    {
        if(Input::post('login'))
        {
            $user = User::user()->validate(
                Input::post('email'),
                Input::post('pass')
            );

            if($user)
            {
                Input::sessionSet(Config::get('user.session_key'), $user->id);    
                Url::redirect(Config::get('admin.default_route'));
            }
            else
                Message::error('Invalid login details.');
        }
    }

    // TODO --------------------------------------------

    public static function resetPassword()
    {
        if(Input::post('reset') && Html::form()->isSecure())
        {
            $user = User::user()->where('email', '=', Input::post('email'))->first();

            if($user)
            {
                if(User::sendResetPasswordEmail($user))
                {
                    Message::ok('Password reset. Check your email.');
                    Url::redirect('admin/login');
                }
                else
                    Message::error('An internal error occured. Please try again.');
            }
            else
                Message::error('Invalid email address.');
        }

        $form = Html::form();

        $form->addText('email', array(
            'attributes' => array(
                'placeholder' => 'Email:',
                'class' => 'span12'
            )
        ));

        $form->addSubmit('reset', 'Reset Password');
        $form->addLink(Url::to('admin/login'), 'Cancel');

        View::data('form', $form->render());
    }

    public static function setPassword($id, $token)
    {
        $valid = true;
        
        if(strlen($token) !== 32)
            $valid = false;

        $user = User::user()->where('id', '=', $id)->andWhere('reset_token', '=', $token)->first();

        if(!$user)
            $valid = false;

        if(!$valid)
        {
            Message::error('Error resetting password. Please try again.');
            Url::redirect('admin/login');
        }

        if(Input::post('set_password') && Html::form()->isSecure())
        {
            $post = Input::clean($_POST);

            if(strlen($post['pass']) < 4)
                Message::error('Password must be at least 4 characters.');

            elseif($post['pass'] !== $post['pass_conf'])
                Message::error('Passwords dont match.');

            else
            {
                $status = User::user()->where('id', '=', $user->id)->update(array(
                    'pass' => md5($post['pass']),
                    'reset_token' => ''
                ));

                if($status)
                {
                    Message::ok('New password set successfully.');
                    Url::redirect('admin/login');
                }
                else
                    Message::error('Error setting password. Please try again.');
            }

        }

        $form = Html::form();

        $form->addPassword('pass', array(
            'attributes' => array(
                'placeholder' => 'Password:',
                'class' => 'span12'
            )
        ));

        $form->addPassword('pass_conf', array(
            'attributes' => array(
                'placeholder' => 'Confirm Password:',
                'class' => 'span12'
            )
        ));

        $form->addSubmit('set_password', 'Set Password');
        $form->addLink(Url::to('admin/login'), 'Cancel');

        View::data('form', $form->render());
    }

    public static function logout()
    {
        unset($_SESSION[Config::get('user.session_key')]);
        Url::redirect('admin/logout');
    }

}
