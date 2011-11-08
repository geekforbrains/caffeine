<?php return array(

    'configs' => array(
        'user.access_denied_redirect' => '',
        'user.login_redirect' => '',
        'user.logout_redirect' => 'admin/logout'
    ),

    'permissions' => array(
        'user.admin' => 'Administer users',
        'user.manage' => 'Manage users',
        'user.create' => 'Create users',
        'user.edit' => 'Edit user profiles',
        'user.edit_mine' => 'Edit my profile'
    ),

    'routes' => array(
        'admin/login' => array(
            'title' => 'Login',
            'callback' => array('user_admin', 'login'),
            'hidden' => true
        ),

        'admin/user' => array(
            'title' => 'Users',
            'redirect' => 'admin/user/manage',
            //'permissions' => array('user.admin')
        ),
        'admin/user/manage' => array(
            'title' => 'Manage',
            'callback' => array('user_admin', 'manage'),
            //'permissions' => array('user.manage')
        ),
        'admin/user/create' => array(
            'title' => 'Create',
            'callback' => array('user_admin', 'create'),
            'permissions' => array('user.create')
        ),
        'admin/user/edit/%d' => array(
            'title' => 'Edit User',
            'callback' => array('user_admin', 'edit'),
            'permissions' => array('user.edit', 'user.edit_mine')
        ),
        'admin/user/delete/%d' => array(
            'title' => 'Delete User',
            'callback' => array('user_admin', 'delete'),
            'permissions' => array('user.delete')
        )
    ),

    'events' => array(
        'router.data' => function($currentRoute, $routeData)
        {
            // If user has permission, fire event thats named after the permission, giving other modules
            // to implement advanced permission check functionality
            if(isset($routeData['permissions']))
            {
                Dev::debug('user', 'Checking route permissions: ' . implode(', ', $routeData['permissions']));

                // If user doesn't have permission, just fail
                // return false;

                // Else fire permission event allowing other modules to determin permission
                // Event::trigger('user.permission[permission.name]')
                foreach($routeData['permissions'] as $k)
                {
                    Event::trigger(sprintf('user.permission[%s]', $k), 
                        array($currentRoute, $routeData), 
                        array('User', 'permissionCallback')
                    );

                    if(User::getPermissionStatus() === false)
                        Dev::debug('user', 'Callback failed');
                }

                View::error(ERROR_ACCESSDENIED);
            }
        },

        // Example of handeling custom permissions via an event
        'user.permission[user.create]' => function($currentRoute, $currentData)
        {
            return false;
        }
    )

);
