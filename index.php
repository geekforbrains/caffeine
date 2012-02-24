<?php

/**
 * Caffeine
 *
 * Caffeine is a simple PHP layer that combines modules through the use of events
 * to form an application.
 *
 * @version 1.0
 * @author Gavin Vickery <gavin@geekforbrains.com>
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class Caffeine {

    /**
     * Allows Caffeine to only be initialized once.
     */
    private static $_inited = false;

    /**
     * Required core modules to load in order for autoloading magic to work.
     */
    private static $_requiredCore = array(
        'module',
        'config',
        'load',
        'site'
    );

    /**
     * Starting point for every page request. Loads required core modules, gets data from url and calls
     * necessary modules to make things happen.
     */
    public static function init()
    {
        if(!self::$_inited)
        {
            self::$_inited = true;

            foreach(self::$_requiredCore as $module)
                require_once(ROOT . 'core/' . $module . '/' . $module . EXT);

            // Set the Load::auto method to handle all class loading from now on
            spl_autoload_register('Load::auto');

            Load::loadSetupFiles();
            date_default_timezone_set(Config::get('system.timezone'));

            Event::trigger('caffeine.started');

            // If maintenance mode has been set in the config, stop everything and load mainteance view
            if(Config::get('system.maintenance_mode'))
                View::error(ERROR_MAINTENANCE);
            else
            {
                list($route, $data) = Router::getRouteData();

                if($data)
                {
                    if(self::_hasPermission($route, $data))
                    {
                        list($module, $controller, $method) = $data['callback'];
                        $params = Router::getParams();

                        // Make sure controller words are upper-case
                        $conBits = explode('_', $controller);
                        foreach($conBits as &$bit)
                            $bit = ucfirst($bit);
                        $controller = implode('_', $conBits);

                        $controller = sprintf('%s_%sController', ucfirst($module), ucwords($controller));

                        // Call the routes controller and method
                        $response = call_user_func_array(array($controller, $method), $params);

                        // Ignore method return values unless they are int, which are assumed to be error codes
                        if(!is_int($response))
                        {
                            Event::trigger('module.response', array($response));
                            View::load($module, $controller, $method);
                        }

                        // Return value was int, load error view
                        else
                            View::error($response);
                    }
                    else
                        View::error(ERROR_ACCESSDENIED);
                }
                else
                {
                    if($route !== '[index]' || !View::directLoad('index'))
                        View::error(ERROR_NOTFOUND);
                }
            }

            View::output();
            Event::trigger('caffeine.finished');
        }
        else
            die('Why are you trying to re-initialize Caffeine?');
    }

    /**
     * Checks if the current user has access to the current route.
     *
     * @return boolean
     */
    private static function _hasPermission($route, $data)
    {
        $hasPermission = false;

        if(empty($data['permissions']) || User::current()->hasPermission($data['permissions']))
        {
            $hasPermission = true;
            Dev::debug('user', 'User has permission');

            // Only do user permission callbacks if not admin, otherwise its pointless
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
            Dev::debug('user', 'User does NOT have permission');

        return $hasPermission;
    }

}

/**
 * These constants allow Caffeine to determine where files are and how to load them.
 * Dont mess with these unless you know what you're doing.
 */
define('ROOT', __DIR__ . '/');
define('EXT', '.php');
define('VERSION', '1.0');

/**
 * These constants are used to load specific error view pages such as 404, 
 * access denied and maintenance.
 */
define('ERROR_NOTFOUND', 404);
define('ERROR_ACCESSDENIED', 401);
define('ERROR_MAINTENANCE', 'maintenance');

/**
 * Sometimes PHP complains if a timezone isn't set, so set UTC initially but we'll set it again
 * using the Config module once its loaded.
 *
 * DONT CHANGE LANGUAGE HERE, DO IT IN CONFIG WITH "system.timezone".
 */
date_default_timezone_set('UTC');

// Vroom, vroom!
session_start();
session_regenerate_id();
Caffeine::init();
