<?php return array(

    'configs' => array(
        'admin.title' => 'Control Panel' // The main title displayed on admin pages
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
                if(!String::startsWith($currentRoute, 'admin/login') && User::current()->isAnonymous())
                    Url::redirect('admin/login');
                else
                {
                    Admin::setInAdmin(true);
                    View::setPath(ROOT . 'core/admin/');
                }
            }
        },

        /**
         * If an admin method returns some data, load it into the current admin theme.
         */
        'module.response' => function($response = null)
        {
            if(Admin::inAdmin())
                View::data('adminContent', $response);
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
            $paths = array(
                sprintf('%s/%s', $controller, $method),
                sprintf('%s_%s', $controller, $method)
            );

            foreach($paths as $path)
            {
                $viewFile = Load::getModulePath($module) . Config::get('view.dir') . $path . EXT;
                Dev::debug('admin', 'Checking for custom view: ' . $viewFile);

                if(file_exists($viewFile))
                {
                    Dev::debug('admin', 'Loading custom view: ' . $viewFile);
                    return $viewFile;
                }
            }
        }

    )

);
