<?php

class Router extends Module {

    private static $_currentRoute = null;
    private static $_routes = array();
    private static $_params = array();

    public static function getCurrentRoute() {
        return self::$_currentRoute;
    }

    public static function getRoutes() {
        return self::$_routes;
    }

    public static function getParams() {
        return self::$_params;
    }

    /**
     * Loads a modules routes. If a modules callback only contains two items,
     * the current module name is prepended to the callback.
     */
    public static function load($routes, $module)
    {
        foreach($routes as &$route)
        {
            if(isset($route['callback']) && count($route['callback']) == 2)
                array_unshift($route['callback'], $module);
        }

        self::$_routes = array_merge($routes, self::$_routes);
    }

    /**
     * Returns the route data associated with the current route. 
     *
     * The route data is set in a modules setup.php file. If no data is
     * found for the current route, boolean false is returned.
     *
     * @return mixed Array of data if route exists, boolean false otherwise
     *
     * TODO Check for invalid characters in URL
     */
    public static function getRouteData()
    {
        $data = false;
        $currentRoute = Config::get('router.default_route');
        if(isset($_GET['r']) && strlen($_GET['r']))
            $currentRoute = $_GET['r'];

        while(true)
        {
            // First search routes with exact match
            if(isset(self::$_routes[$currentRoute]))
                $data = self::$_routes[$currentRoute];

            // If no exact match, check matches with regex
            foreach(self::$_routes as $route => $routeData)
            {
                /*
                $regexRoute = str_replace(':num', '([0-9]+)', $route);
                $regexRoute = str_replace(':slug', '([A-Za-z0-9\-]+)', $regexRoute);
                $regexRoute = str_replace(':any', '(.*?)', $regexRoute);
                */

                $regexRoute = String::regify($route);

                if(preg_match('@^' . $regexRoute . '$@', $currentRoute, $matches))
                {
                    self::$_params = array_slice($matches, 1);
                    $data = $routeData;
                }
            }

            // If no route data was found, must be 404
            if(!$data)
                break;

            // If route had redirect, set new route and try again
            elseif(isset($data['redirect']))
                $currentRoute = $data['redirect'];

            // If data was found and not redirecting, must be the route we want
            elseif($data)
            {
                // First check permissions, if any, for current user
                self::$_currentRoute = array(
                    'route' => $currentRoute,
                    'data' => $data
                );

                break;
            }
        }

        // Let other areas of the application make use of route data
        Event::trigger('router.data', array($currentRoute, $data));

        return $data;
    }

}
