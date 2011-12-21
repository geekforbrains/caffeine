<?php return array(

    'configs' => array(
        'user.access_denied_redirect' => '',
        'user.login_redirect' => 'admin/user',
        'user.logout_redirect' => 'admin/login',
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
            'callback' => array('user_login', 'login'),
            'hidden' => true
        ),
        'admin/logout' => array(
            'title' => 'Logout',
            'callback' => array('user_login', 'logout'),
            'hidden' => true
        ),

        'admin/user' => array(
            'title' => 'Users',
            'redirect' => 'admin/user/manage',
            'permissions' => array('user.admin')
        ),
        'admin/user/manage' => array(
            'title' => 'Manage',
            'callback' => array('user_admin', 'manage'),
            'permissions' => array('user.admin')
        ),
        'admin/user/create' => array(
            'title' => 'Create',
            'callback' => array('user_admin', 'create'),
            'permissions' => array('user.create')
        ),
        'admin/user/edit/%d' => array(
            'title' => 'Edit User',
            'callback' => array('user_admin', 'edit'),
            'hidden' => true,
            'permissions' => array('user.edit', 'user.edit_mine')
        ),
        'admin/user/delete/%d' => array(
            'title' => 'Delete User',
            'callback' => array('user_admin', 'delete'),
            'hidden' => true,
            'permissions' => array('user.delete')
        ),

        'admin/user/role' => array(
            'title' => 'Roles',
            'redirect' => 'admin/user/role/manage',
            'permissions' => array('user.admin_roles')
        ),
        'admin/user/role/manage' => array(
            'title' => 'Manage',
            'callback' => array('user_role_admin', 'manage'),
            'permissions' => array('user.manage_roles')
        ),
        'admin/user/role/create' => array(
            'title' => 'Create',
            'callback' => array('user_role_admin', 'create'),
            'permissions' => array('user.create_roles')
        ),
        'admin/user/role/edit/%d' => array(
            'title' => 'Edit Role',
            'callback' => array('user_role_admin', 'edit'),
            'permissions' => array('user.edit_roles'),
            'hidden' => true
        ),
        'admin/user/role/delete/%d' => array(
            'title' => 'Delete Role',
            'callback' => array('user_role_admin', 'delete'),
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