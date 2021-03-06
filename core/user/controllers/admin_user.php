<?php

class User_Admin_UserController extends Controller {

    /**
     * Displays a table of current users.
     */
    public static function manage()
    {
        $table = Html::table();
        $header = $table->addHeader();
        $header->addCol('Username', array('colspan' => 2));

        $users = User::user()->orderBy('email')->all();

        if($users)
        {
            foreach($users as $user)
            {
                $row = $table->addRow();
                $row->addCol(Html::a($user->email, 'admin/user/edit/' . $user->id));

                if($user->is_admin <= 0)
                {
                    $row->addCol(
                        Html::a('Delete', 'admin/user/delete/' . $user->id), 
                        array(
                            'class' => 'right',
                            'onclick' => "return confirm('Delete this user?')"
                        )
                    );
                }
                else
                    $row->addCol('&nbsp;');
            }
        }
        else
            $table->addRow()->addCol('<em>No users</em>', array('colspan' => 2));

        return array(
            'title' => 'Manage Users',
            'content' => $table->render()
        );
    }

    /**
     * Displays a form for creating a new user.
     */
    public static function create()
    {
        if(isset($_POST['create_user']) && Html::form()->validate())
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
                        Db::table('habtm_userroles_userusers')->insert(array(
                            'user_role_id' => $roleId,
                            'user_user_id' => $userId
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

        $options = array();
        $roles = User::role()->all();

        foreach($roles as $role)
            $options[$role->id] = $role->name;

        $fields[] = array(
            'fields' => array(
                'email' => array(
                    'title' => 'Email',
                    'type' => 'text',
                    'validate' => array('required', 'email')
                ),
                'password' => array(
                    'title' => 'Password',
                    'type' => 'password',
                    'validate' => array('required', 'min:4')
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
                'create_user' => array(
                    'value' => 'Create User',
                    'type' => 'submit'
                )
            )
        );

        return array(
            'title' => 'Create User',
            'content' => Html::form()->build($fields)
        );
    }

    /**
     * Displays a form for editing a current user.
     */
    public static function edit($id)
    {
        $user = User::user()->find($id);

        if(isset($_POST['update_user']))
        {
            // First check if new email is already in use
            if($_POST['email'] == $user->email || !User::user()->where('email', '=', $_POST['email'])->first())
            {
                $status = User::user()->where('id', '=', $id)->update(array(
                    'email' => $_POST['email'],
                    'pass' => strlen($_POST['pass']) ? md5($_POST['pass']) : $user->pass
                ));

                Db::table('habtm_userroles_userusers')->where('user_user_id', '=', $user->id)->delete();

                if(isset($_POST['role_id']))
                {
                    foreach($_POST['role_id'] as $roleId)
                    {
                        Db::table('habtm_userroles_userusers')->insert(array(
                            'user_role_id' => $roleId,
                            'user_user_id' => $user->id
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
        $selectedRoles = Db::table('habtm_userroles_userusers')->where('user_user_id', '=', $id)->all();

        foreach($roles as $role)
            $options[$role->id] = $role->name;

        if($selectedRoles)
            foreach($selectedRoles as $role)
                $selected[] = $role->user_role_id;

        $fields[] = array(
            'fields' => array(
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
                'update_user' => array(
                    'value' => 'Update User',
                    'type' => 'submit'
                )
            )
        );

        return array(
            array(
                'title' => 'Edit User',
                'content' => Html::form()->build($fields)
            )
        );
    }

    /**
     * Deletes a user and redirects to manage page.
     */
    public static function delete($id)
    {
        if(User::user()->find($id)->is_admin > 0)
            Message::error('Admin user cannot be deleted.');
        else
        {
            if($response = User::user()->delete($id))
                Message::ok('User deleted successfully.');
            else
                Message::error('Error deleting user.');
        }

        Url::redirect('admin/user/manage');
    }

}
