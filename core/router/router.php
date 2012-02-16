<?php

class Router extends Module {

    /**
     * TODO
     */
    private static $_currentRoute = null;

    /**
     * TODO
     */
    private static $_routes = array();

    /**
     * TODO
     */
    private static $_params = array();

    /**
     * TODO
     */
    //private static $_segments = null;

    /**
     * TODO
     */
    public static function getCurrentRoute() {
        return self::$_currentRoute;
    }

    /**
     * TODO
     */
    public static function getRoutes() {
        return self::$_routes;
    }

    /**
     * TODO
     */
    public static function getParams() {
        return self::$_params;
    }

    /**
     * TODO
     */
    public static function getParam($num)
    {
        if(isset(self::$_params[$num]))
            return self::$_params[$num];
        return false;
    }

    /**
     * !!! THIS HAS BEEN MOVED TO THE URL MODULE, AND IS NOW DEPRECATED !!!
     *
     * Returns the current URL segment, after the application base, as an array
     */
    public static function getSegments()
    {
        /*
        if(is_null(self::$_segments))
            self::$_segments = explode('/', Url::current());

        return self::$_segments;
        */
        return Url::segments();
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
        
        /*
        $defaultRoute = Config::get('router.default_route');
        $currentRoute = $defaultRoute;

        if(isset($_GET['r']) && strlen($_GET['r']))
            $currentRoute = $_GET['r'];

        $currentRoute = rtrim($currentRoute, '/');

        // Look for language code, and modify route if need be
        if(Multilanguage::routeHasLangCode($currentRoute))
        {
            $currentRoute = ltrim(substr($currentRoute, 3), '/');

            // If current route is empty now (due to being at base url with language code (ex: /<code>), set to default
            if(!strlen($currentRoute))
                $currentRoute = $defaultRoute;
        }
        */

        $currentRoute = Url::current();

        if($currentRoute == '/')
            $currentRoute = Config::get('router.default_route');
        else
            $currentRoute = ltrim($currentRoute, '/'); // We dont want leading slash of the current url for routes

        while(true)
        {
            // First search routes with exact match
            if(isset(self::$_routes[$currentRoute]))
                $data = self::$_routes[$currentRoute];

            // If no exact match, check matches with regex
            foreach(self::$_routes as $route => $routeData)
            {
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
        
        if($data && !isset($data['permissions']))
            $data['permissions'] = array();

        // Let other areas of the application make use of route data
        Event::trigger('router.data', array($currentRoute, $data));

        return array($currentRoute, $data);
    }

}
