<?php

class User_User_AdminController extends Controller {

    /**
     * Displays a table of current users.
     */
    public static function manage()
    {
        $headers = array(
            array(
                'Username',
                'attributes' => array('colspan' => 2)
            )
        );

        $rows = array();
        if($users = User::user()->orderBy('email')->all())
        {
            foreach($users as $user)
            {
                $rows[] = array(
                    Html::a()->get($user->email, 'admin/user/edit/' . $user->id),
                    array(
                        Html::a()->get('Delete', 'admin/user/delete/' . $user->id),
                        'attributes' => array('align' => 'right')
                    )
                );
            }
        }
        else
        {
            $rows[] = array(
                array(
                    '<em>No users.</em>',
                    'attributes' => array('colspan' => '2')
                )
            );
        }

        return Html::table()->build($headers, $rows);
    }

    /**
     * Displays a form for creating a new user.
     */
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
                    Message::ok('User created successfully.');
                else
                    Message::error('Error creating user.');
            }
            else
                Message::error('A user with that email exists.');
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

    /**
     * Displays a form for editing a current user.
     */
    public static function edit($id)
    {
        $user = User::user()->find($id);

        if($_POST)
        {
            // First check if new email is already in use
            if($_POST['email'] == $user->email || !User::user()->where('email', '=', $_POST['email'])->first())
            {
                $user->email = $_POST['email'];

                if(strlen($_POST['pass']))
                    $user->pass = md5($_POST['pass']);

                if($user->save())
                    Message::ok('User updated successfully.');
                else
                    Message::error('Error updating user.');
            }
            else
                Message::error('That email address is already in use.');
        }

        $fields = array(
            'email' => array(
                'title' => 'Email',
                'type' => 'text',
                'default_value' => $user->email
            ),
            'pass' => array(
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

    /**
     * Deletes a user and redirects to manage page.
     */
    public static function delete($id)
    {
        if($response = User::user()->delete($id))
            Message::ok('User deleted successfully.');
        else
            Message::error('Error deleting user.');

        Url::redirect('admin/user/manage');
    }

}
