<?php 

class User_LoginController extends Controller {

    public static function login()
    {
        if(Input::post('login') && Html::form()->isSecure())
        {
            $post = Input::clean($_POST);

            $user = User::user()
                ->where('email', '=', $post['email'])
                ->andWhere('pass', '=', md5($post['password']))->first();

            if($user)
            {
                Input::sessionSet(Config::get('user.session_key'), $user->id);
                Url::redirect('admin');
            }
            else
                Message::error('Invalid login details.');
        }

        $form = Html::form();

        $form->addText('email', array(
            'attributes' => array(
                'placeholder' => 'Email:',
                'class' => 'span12'
            )
        ));

        $form->addPassword('password', array(
            'attributes' => array(
                'placeholder' => 'Password:',
                'class' => 'span12'
            )
        ));

        $form->addSubmit('login', 'Login');
        $form->addLink('admin/reset-password', 'Forgot password?');
        
        View::data('form', $form->render());
    }

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
