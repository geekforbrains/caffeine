<?php

class User_Admin_UserController extends Controller {

    /**
     * Displays a table of current users.
     */
    public static function manage()
    {
        return array(
            'users' => User::user()->orderBy('email')->all()
        );
    }

    /**
     * Displays a form for creating a new user.
     */
    public static function create()
    {
        if(Input::post('create_user'))
        {
            Validate::check('email', array('required', 'email'));
            Validate::check('pass', array('required', 'min:4'));
            Validate::check('pass_conf', array('matches:pass'));

            if(Validate::passed())
            {
                $post = Input::clean($_POST);

                if(!User::user()->emailInUse($post['email']))
                {
                    $userId = User::user()->insert(array(
                        'email' => $post['email'],
                        'pass' => md5($post['password'])
                    ));

                    if($userId && isset($post['role_id']))
                    {
                        foreach($post['role_id'] as $roleId)
                        {
                            Db::habtm('user.role', 'user.user')->insert(array(
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
                        Message::error('Error creating user, please try again.');
                }
                else
                    Message::error('That email is already in use.');
            }
        }

        return array(
            'roles' => User::role()->orderBy('name')->all()
        );
    }

    /**
     * Displays a form for editing a current user.
     */
    public static function edit($id)
    {
        if(!$user = User::user()->find($id))
            return 404;

        if(Input::post('update_user'))
        {
            Validate::check('email', array('required', 'email'));

            if(Input::post('pass'))
            {
                Validate::check('pass', array('min:4'));
                Validate::check('pass_conf', array('matches:pass'));
            }

            if(Validate::passed())
            {
                $post = Input::clean($_POST);

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
        }

        return array(
            'user' => $user,
            'roles' => User::role()->orderBy('name')->all()
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
