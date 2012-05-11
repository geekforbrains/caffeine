<?php 

class User_LoginController extends Controller {

    public static function login()
    {
        if(Input::post('login'))
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

    }

    public static function logout()
    {
        unset($_SESSION[Config::get('user.session_key')]);
        Url::redirect('admin/logout');
    }

}
