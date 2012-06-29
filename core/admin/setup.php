<?php return array(

    'configs' => array(
        /**
         * The default route to redirect to when logging into admin or accessing route "admin/".
         */
        'admin.default_route' => 'admin/user/manage'
    ),

    'permissions' => array(
        'admin.access' => 'Access to admin'
    ),

    'routes' => array(
        'admin' => array(
            'title' => 'Admin',
            'callback' => array('admin', 'redirect'),
        ),
        'admin/install' => array(
            'title' => 'Install',
            'callback' => array('admin', 'install'),
            'hidden' => true
        ),
        'admin/login' => array(
            'title' => 'Login',
            'callback' => array('admin', 'login'),
            'hidden' => true
        ),
        'admin/reset-password' => array(
            'title' => 'Reset Password',
            'callback' => array('admin', 'resetPassword'),
            'hidden' => true
        ),
        'admin/set-password/:id/:slug' => array(
            'title' => 'Set Password',
            'callback' => array('admin', 'setPassword'),
            'hidden' => true
        ),
        'admin/logout' => array(
            'title' => 'Logout',
            'callback' => array('admin', 'logout'),
            'hidden' => true
        ),
    ),

    'events' => array(
        
        /**
         * If the current route starts with admin/ change the views path to the
         * admin views directory. This is to allow the admin area to have its own "theme".
         */
        'router.data' => function($currentRoute, $data)
        {
            if(String::startsWith($currentRoute, 'admin'))
            {
                if(!Admin::isConfigured())
                {
                    if(!String::startsWith($currentRoute, 'admin/install'))
                        Url::redirect('admin/install');
                }

                elseif(
                    !String::startsWith($currentRoute, 'admin/login') && 
                    !String::startsWith($currentRoute, 'admin/reset-password') &&
                    !String::startsWith($currentRoute, 'admin/set-password'))
                {
                    $noAccess = !User::current()->hasPermission('admin.access');
                    $isAnon = User::current()->isAnonymous();

                    if(!$isAnon && $noAccess)
                    {
                        Message::error('You do not have admin access permissions.');
                        unset($_SESSION[Config::get('user.session_key')]);
                    }

                    if($isAnon || $noAccess)
                        Url::redirect('admin/login');
                }

                Admin::setInAdmin(true);
                View::setPath(ROOT . 'core/admin/');
            }
        },

        /**
         * Checks if the current module has a views directory with a custom admin view named after the controller
         * and method.
         *
         * Check order:
         * 1. views/controller/method.php
         * 2. views/controller_method.php
         */
        'view.load' => function($module, $controller, $method)
        {
            if(Admin::inAdmin())
            {
                $paths = array(
                    sprintf('%s/%s', $controller, $method),
                    sprintf('%s_%s', $controller, $method)
                );

                foreach($paths as $path)
                {
                    $viewFile = Load::getModulePath($module) . Config::get('view.dir') . $path . EXT;
                    Log::debug('admin', 'Checking for custom view: ' . $viewFile);

                    if(file_exists($viewFile))
                    {
                        Log::debug('admin', 'Loading custom view: ' . $viewFile);
                        return $viewFile;
                    }
                }
            }
        }

    )

);
