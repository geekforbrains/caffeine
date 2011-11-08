<?php

class Router extends Module {

    private static $_routes = array();
    private static $_params = array();

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
            if(count($route['callback']) == 2)
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
        $currentRoute = Config::get('router.default_route');
        if(isset($_GET['r']) && strlen($_GET['r']))
            $currentRoute = $_GET['r'];

        echo "Starting Route: $currentRoute<br />";

        // First search routes with exact match
        if(isset(self::$_routes[$currentRoute]))
            $currentRoute = self::$_routes[$currentRoute];

        // If no exact match, check matches with regex
        foreach(self::$_routes as $route => $redirect)
        {
            $regexRoute = str_replace(':num', '([0-9]+)', $route);
            $regexRoute = str_replace(':slug', '([A-Za-z0-9\-]+)', $regexRoute);
            $regexRoute = str_replace(':any', '(.*?)', $regexRoute);

            if(preg_match('@^' . $regexRoute . '$@', $currentRoute, $matches))
            {
                //self::$_params = array_slice($matches, 1);
                $params = array_slice($matches, 1);

                // Replace nums with params in redirect, if any
                // example: blog/post/:slug => blog/posts/single/$1 (blog/posts/single/my-slug)
                for($i = 0; $i < count($params); $i++)
                    $redirect = str_replace('$' . ($i + 1), $params[$i], $redirect);

                $currentRoute = $redirect;
                //return $data;
            }
        }

        echo "Finished Route: $currentRoute<br />";

        // Parse module, controller, method and params out of route
        // Example: http://domain.com/module/controller/method/param1/param2/etc...
        $bits = explode('/', $currentRoute);

        // Routes must have a minimum of the module, controller and method in the route
        // Also, methods cant start with an underscore, this hides public methods that shouldnt be accessed
        // For shorter routes, use custom routes in setup.php
        if(count($bits) < 3 || $bits[2]{0} == '_')
            return false;

        return $bits;
        /*
        return array(
            $bits[0],
            $bits[1],
            $bits[2],
            (count($bits) > 3) ? array_slice($bits, 3) : array()
        );
        */
    }

}
