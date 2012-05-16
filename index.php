<?php

/**
 * Caffeine
 *
 * A simple PHP framework that combines modules through the use of routes and 
 * events to form an application.
 *
 * @version 1.0.2
 * @date 2012-03-24
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
        'event',
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

            // If CLI mode, everything thats needed has been loaded
            if(IS_CLI)
                return;

            date_default_timezone_set(Config::get('system.timezone'));

            Event::trigger('caffeine.started');

            // If maintenance mode has been set in the config, stop everything and load mainteance view
            if(Config::get('system.maintenance_mode'))
                View::error(ERROR_MAINTENANCE);
            else
            {
                list($route, $data) = Router::getRouteData();

                Event::trigger('caffeine.ready');

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
                        if(method_exists($controller, $method))
                        {
                            $response = call_user_func_array(array($controller, $method), $params);

                            if(!self::_isErrorResponse($response))
                            {
                                Event::trigger('module.response', array($response));
                                View::load($module, $controller, $method);
                            }
                            else
                                View::error($response);
                        }
                        else
                        {
                            Log::error($module, sprintf('The method %s::%s() called by route %s doesn\'t exist.',
                                $controller, $method, $route));
                            
                            View::error(ERROR_500);
                        }
                    }
                    else
                        View::error(ERROR_ACCESSDENIED);
                }
                else
                {
                    if($route !== '[index]' || !View::directLoad('index'))
                        View::error(ERROR_404);
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
            Log::debug('user', 'User has permission to access this route.');

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
                        Log::debug('user', 'Custom permission callback failed, setting access denied');
                        $hasPermission = false;
                        break;
                    }
                }
            }
        }
        else
            Log::debug('user', 'User does NOT have permission to access this route.');

        return $hasPermission;
    }

    /**
     * Checks if a Controller response is related to an error constant.
     *
     * @param mixed $response The response to compare errors to.
     * @return boolean
     */
    private static function _isErrorResponse($response)
    {
        return in_array($response, array(
            ERROR_NOTFOUND, // DEPRECATED
            ERROR_404,
            ERROR_500,
            ERROR_ACCESSDENIED,
            ERROR_MAINTENANCE
        ));
    }

}

/**
 * These constants allow Caffeine to determine where files are and how to load them.
 * Dont mess with these unless you know what you're doing.
 */
define('ROOT', __DIR__ . '/');
define('EXT', '.php');
define('VERSION', '1.0.2');
define('IS_CLI', defined('CLI'));

/**
 * These constants are used to load specific error view pages such as 404, 
 * access denied and maintenance.
 */
define('ERROR_NOTFOUND', 404); // !! DEPRECATED !!
define('ERROR_404', 404);
define('ERROR_500', 500);
define('ERROR_ACCESSDENIED', 'access_denied');
define('ERROR_MAINTENANCE', 'maintenance');

/**
 * Sometimes PHP complains if a timezone isn't set, so set UTC initially but we'll set it again
 * using the Config module once its loaded.
 *
 * DONT CHANGE TIMEZONE HERE, DO IT IN CONFIG WITH "system.timezone".
 */
date_default_timezone_set('UTC');

/**
 * Only load sessions if we're running in default (HTTP) mode.
 */
if(!IS_CLI)
{
    session_start();
    session_regenerate_id();
}

// Vroom, vroom!
Caffeine::init();
