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
        if(Input::post('create_user') && Html::form()->validate())
        {
            $post = Input::clean($_POST);

            if(!User::user()->where('email', 'LIKE', $post['email'])->first())
            {
                $userId = User::user()->insert(array(
                    'email' => $post['email'],
                    'pass' => md5($post['password'])
                ));

                if($userId && isset($post['role_id']))
                {
                    foreach($post['role_id'] as $roleId)
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

        $form = Html::form()->addFieldset();

        $form->addText('email', array(
            'title' => 'Email',
            'validate' => array('required', 'email')
        ));

        $form->addPassword('password', array(
            'title' => 'Password',
            'validate' => array('required', 'min:4')
        ));

        $form->addPassword('confirm_password', array(
            'title' => 'Confirm Password',
            'validate' => array('required', 'matches:password')
        ));

        $form->addSelect('role_id[]', array(
            'title' => 'Roles',
            'options' => User::role()->orderBy('name')->all(),
            'option_key' => 'id',
            'option_value' => 'name',
            'attributes' => array(
                'multiple' => 'multiple'
            )
        ));

        $form->addSubmit('create_user', 'Create User');
        $form->addLink(Url::to('admin/user/manage'), 'Cancel');

        return array(
            'title' => 'Create User',
            'content' => $form->render()
        );

    }

    /**
     * Displays a form for editing a current user.
     */
    public static function edit($id)
    {
        if(!$user = User::user()->find($id))
            return 404;

        if(Input::post('update_user') && Html::form()->validate())
        {
            $post = Input::clean($_POST);

            if($post['email'] == $user->email || !User::user()->where('email', '=', $post['email'])->first())
            {
                $status = User::user()->where('id', '=', $id)->update(array(
                    'email' => $post['email'],
                    'pass' => strlen($post['pass']) ? md5($post['pass']) : $user->pass
                ));

                Db::habtm('user.role', 'user.user')->where('user_user_id', '=', $user->id)->delete();

                if(isset($post['role_id']))
                {
                    foreach($post['role_id'] as $roleId)
                    {
                        Db::habtm('user.role', 'user.user')->insert(array(
                            'user_role_id' => $roleId,
                            'user_user_id' => $user->id
                        ));
                    }
                }

                if($status)
                {
                    Message::ok('User updated successfully.');
                    $user = User::user()->find($id); // Get updated user for form
                }
                else
                    Message::error('Error updating user.');
            }
            else
                Message::error('That email address is already in use.');
        }

        $form = Html::form()->addFieldset();

        $form->addText('email', array(
            'title' => 'Email',
            'value' => $user->email,
            'validate' => array('required')
        ));

        $form->addPassword('pass', array(
            'title' => 'Password'
        ));

        $form->addSelect('role_id[]', array(
            'title' => 'Roles',
            'options' => User::role()->orderBy('name')->all(),
            'option_key' => 'id',
            'option_value' => 'name',
            'selected' => Db::habtm('user.role', 'user.user')->where('user_user_id', '=', $id)->all(),
            'selected_key' => 'user_role_id',
            'attributes' => array(
                'multiple' => 'multiple'
            )
        ));

        $form->addSubmit('update_user', 'Update User');
        $form->addLink(Url::previous(), 'Cancel');

        return array(
            'title' => 'Edit User',
            'content' => $form->render()
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
