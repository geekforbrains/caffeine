<?php return array(

    'configs' => array(
        'user.session_key' => 'user_id'
    ),

    'permissions' => array(
        'user.admin' => 'Administer users',
        'user.create' => 'Create users',
        'user.edit' => 'Edit user profiles',
        'user.edit_mine' => 'Edit my profile',
        'user.delete' => 'Delete users',

        'user.admin_roles' => 'Administer roles',
        'user.manage_roles' => 'Manage roles',
        'user.create_roles' => 'Create roles',
        'user.edit_roles' => 'Edit roles',
        'user.delete_roles' => 'Delete roles'
    ),

    'routes' => array(
        'admin/login' => array(
            'title' => 'Login',
            'callback' => array('login', 'login'),
            'hidden' => true
        ),
        'admin/reset-password' => array(
            'title' => 'Reset Password',
            'callback' => array('login', 'resetPassword'),
            'hidden' => true
        ),
        'admin/logout' => array(
            'title' => 'Logout',
            'callback' => array('login', 'logout'),
            'hidden' => true
        ),

        'admin/user' => array(
            'title' => 'Users',
            'redirect' => 'admin/user/manage',
            'permissions' => array('user.admin')
        ),
        'admin/user/manage' => array(
            'title' => 'Manage Users',
            'callback' => array('admin_user', 'manage'),
            'permissions' => array('user.admin')
        ),
        'admin/user/create' => array(
            'title' => 'Create User',
            'callback' => array('admin_user', 'create'),
            'permissions' => array('user.create')
        ),
        'admin/user/edit/%d' => array(
            'title' => 'Edit User',
            'callback' => array('admin_user', 'edit'),
            'hidden' => true,
            'permissions' => array('user.edit', 'user.edit_mine')
        ),
        'admin/user/delete/%d' => array(
            'callback' => array('admin_user', 'delete'),
            'hidden' => true,
            'permissions' => array('user.delete')
        ),

        'admin/role' => array(
            'title' => 'Roles',
            'redirect' => 'admin/user/role/manage',
            'permissions' => array('user.admin_roles')
        ),
        'admin/role/manage' => array(
            'title' => 'Manage Roles',
            'callback' => array('admin_role', 'manage'),
            'permissions' => array('user.manage_roles')
        ),
        'admin/role/create' => array(
            'title' => 'Create Role',
            'callback' => array('admin_role', 'create'),
            'permissions' => array('user.create_roles')
        ),
        'admin/role/edit/%d' => array(
            'title' => 'Edit Role',
            'callback' => array('admin_role', 'edit'),
            'permissions' => array('user.edit_roles'),
            'hidden' => true
        ),
        'admin/role/delete/%d' => array(
            'callback' => array('admin_role', 'delete'),
            'permissions' => array('user.delete_roles'),
            'hidden' => true
        )
    ),

    'events' => array(
        'user.permission[user.edit_mine]' => function($route, $data)
        {
            $params = Router::getParams();
            $userId = $params[0];

            if(User::current()->id == $userId)
                return true;
            return false;
        }
    )

);
