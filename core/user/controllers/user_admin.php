<?php

class User_User_AdminController extends Controller {

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
        unset($_SESSION['user']);
        Url::redirect(Config::get('user.logout_redirect'));
    }

    public static function manage()
    {
        $headers = array(
            'Username',
            array(
                Html::a()->get('Delete', 'delete'),
                'attributes' => array(
                    'style' => 'text-align: right'
                )
            )
        );

        $rows = array();

        if($users = User::user()->all())
        {
            foreach($users as $user)
            {
                $rows[] = array(
                    Html::a()->get($user->email, 'admin/user/edit/' . $user->id),
                    array(
                        Html::a()->get('Delete', 'admin/user/delete/' . $user->id),
                        'attributes' => array(
                            'align' => 'right'
                        )
                    )
                );
            }
        }
        else
            $rows[] = array('<em>No users.</em>');


        return Html::table()->build($headers, $rows);
    }

    public static function create()
    {
        if($_POST)
        {
            if(!User::user()->where('email', '=', $_POST['email'])->first())
            {
                $user = User::user();
                $user->account = 0;
                $user->email = $_POST['email'];
                $user->pass = md5($_POST['password']);

                if($user->save())
                    Message::set('success', 'User created successfully.');
                else
                    Message::set('error', 'Error creating user.');
            }
            else
                Message::set('error', 'A user with that email exists.');
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
        $user = User::user()->find($id);

        $fields = array(
            'email' => array(
                'title' => 'Email',
                'type' => 'text',
                'default_value' => $user->email
            ),
            'password' => array(
                'title' => 'Password',
                'type' => 'password'
            ),
            'submit' => array(
                'value' => 'Update User',
                'type' => 'submit'
            )
        );

        return Html::form()->build($fields);
    }

    public static function delete($id)
    {
        if(User::user()->delete($id))
            Message::set('success', 'User deleted successfully.');
        else
            Message::set('error', 'Error deleting user.');

        Url::redirect('admin/user/manage');
    }

}
