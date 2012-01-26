<?php
session_start();

define('ROOT', __DIR__ . '/');
define('EXT', '.php');
define('VERSION', '1.0');

define('ERROR_NOTFOUND', 404);
define('ERROR_ACCESSDENIED', 401);
define('ERROR_MAINTENANCE', 'maintenance');

$core = array(
    'module', // Module must be loaded first; everything extends it
    'config',
    'load',
    'site'
);


foreach($core as $module)
    require_once(ROOT . 'core/' . $module . '/' . $module . EXT);

spl_autoload_register('Load::auto');
Load::loadSetupFiles();
Db::install();

Event::trigger('caffeine.started');

if(!Variable::get('system.maintenance_mode', false))
{
    list($route, $data) = Router::getRouteData();

    if($data)
    {
        $hasPermission = false;

        if(empty($data['permissions']) || User::current()->hasPermission($data['permissions']))
        {
            $hasPermission = true;
            Dev::debug('user', 'User has permission');

            // Only do callbacks if user is admin, otherwise always allow
            if(User::current()->is_admin <= 0)
            {
                foreach($data['permissions'] as $k)
                {
                    Event::trigger(sprintf('user.permission[%s]', $k), 
                        array($route, $data), 
                        array('User', 'permissionCallback')
                    );

                    if(User::getPermissionStatus() === false)
                    {
                        Dev::debug('user', 'Custom permission callback failed, setting access denied');
                        $hasPermission = false;
                        break;
                    }
                }
            }
        }
        else
            Dev::debug('user', 'User does NOT have permissions');

        if($hasPermission)
        {
            list($module, $controller, $method) = $data['callback'];
            $params = Router::getParams();

            // Make sure controller words are upper
            $conBits = explode('_', $controller);
            foreach($conBits as &$bit)
                $bit = ucfirst($bit);
            $controller = implode('_', $conBits);

            $controller = sprintf('%s_%sController', ucfirst($module), ucwords($controller));
            $response = call_user_func_array(array($controller, $method), $params);

            // If the response is an int, assume its a pre-defined error code
            if(!is_int($response))
            {
                Event::trigger('module.response', array($response));
                View::load($module, $controller, $method);
            }

            // All other method responses are assumed to be errors. Load their views.
            else
                View::error($response);
        }
        else
            View::error(ERROR_ACCESSDENIED);
    }
    else
    {
        if($route != '[index]' || !View::directLoad('index'))
            View::error(ERROR_NOTFOUND);
    }
}
else  // In maintenance mode!!!
    View::error(ERROR_MAINTENANCE);

View::output();
Event::trigger('caffeine.finished');
