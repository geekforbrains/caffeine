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
            if(Html::form()->validate())
            {
                if(!User::user()->where('email', 'LIKE', $_POST['email'])->first())
                {
                    $userId = User::user()->insert(array(
                        'email' => $_POST['email'],
                        'pass' => md5($_POST['password'])
                    ));

                    if($userId && isset($_POST['role_id']))
                    {
                        foreach($_POST['role_id'] as $roleId)
                        {
                            Db::table('roles_users')->insert(array(
                                'role_id' => $roleId,
                                'user_id' => $userId
                            ));
                        }
                    }

                    if($userId)
                    {
                        Message::ok('User created successfully.');
                        Url::redirect('admin/user/manage');
                    }
                    else
                        Message::error('Error creating user.');
                }
                else
                    Message::error('A user with that email exists.');
            }
        }

        $options = array();
        $roles = User::role()->all();

        foreach($roles as $role)
            $options[$role->id] = $role->name;

        $fields = array(
            'email' => array(
                'title' => 'Email',
                'type' => 'text',
                'validate' => array('required', 'email')
            ),
            'password' => array(
                'title' => 'Password',
                'type' => 'password',
                'validate' => array('required')
            ),
            'confirm_password' => array(
                'title' => 'Confirm Password',
                'type' => 'password',
                'validate' => array('required', 'matches:password')
            ),
            'role_id[]' => array(
                'title' => 'Roles',
                'type' => 'select',
                'options' => $options,
                'attributes' => array('multiple' => 'multiple')
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
                $status = User::user()->where('id', '=', $id)->update(array(
                    'email' => $_POST['email'],
                    'pass' => isset($_POST['pass']) ? md5($_POST['pass']) : $user->pass
                ));

                // Always clear current roles when updating, new roles will be inserted after
                Db::table('roles_users')->where('user_id', '=', $user->id)->delete();

                if(isset($_POST['role_id']))
                {
                    foreach($_POST['role_id'] as $roleId)
                    {
                        Db::table('roles_users')->insert(array(
                            'role_id' => $roleId,
                            'user_id' => $user->id
                        ));
                    }
                }

                if($status)
                    Message::ok('User updated successfully.');
                else
                    Message::error('Error updating user.');
            }
            else
                Message::error('That email address is already in use.');
        }

        $options = array();
        $selected = array();

        $roles = User::role()->all();
        $selectedRoles = Db::table('roles_users')->where('user_id', '=', $id)->all();

        foreach($roles as $role)
            $options[$role->id] = $role->name;

        foreach($selectedRoles as $role)
            $selected[] = $role->role_id;

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
            'role_id[]' => array(
                'title' => 'Roles',
                'type' => 'select',
                'options' => $options,
                'selected' => $selected,
                'attributes' => array('multiple' => 'multiple')
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
