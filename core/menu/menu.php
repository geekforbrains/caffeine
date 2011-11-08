<?php

// TODO Auto add classes (first, active, last etc.)
// TODO Add menus to database for quicker access, based on md5 hash of html string (create Cache modue?)

class Menu {

    /**
     * Stores sorted routes to be used for building Menu.
     */
    private static $_sorted = array();

    public static function build($depth = -1, $offset = null, $data = array())
    {
        $sorted = self::_getSorted();

        if(!is_null($offset))
            $sorted = self::_offset($sorted, $offset);

        if($depth >= 0)
            $sorted = self::_depth($sorted, $depth);

        if($sorted)
            return self::_getHtml($sorted, $data);

        return null;
    }

    private static function _offset($sorted, $offset)
    {
        if(strstr($offset, '%'))
        {
            // We want the actual route being used to compare this, not the current url
            // Reason being that the current url might actually be a redirect to the route we want
            $currentRoute = Router::getCurrentRoute();
            if(preg_match('@' . String::regify($offset) . '@', $currentRoute['route'], $m))
                $offset = $m[0];
        }

        foreach($sorted as $route => $routeData)
        {
            if($route == $offset)
                return $routeData['children'];

            elseif(String::startsWith($offset, $route))
                $sorted = self::_offset($routeData['children'], $offset); 
        }

        return $sorted;
    }

    private static function _depth($sorted, $depth)
    {
        $depth--;
        $tmp = array();

        foreach($sorted as $route => $routeData)
        {
            $tmp[$route] = $routeData;
            $tmp[$route]['children'] = array(); // Need to reset children to manually manage depth

            if($depth >= 0)
                $tmp[$route]['children'] = self::_depth($routeData['children'], $depth);
        }

        return $tmp;
    }

    private static function _getHtml($sorted, $data)
    {
        // The current route is used to determine if the menu item is active or not
        $currentRoute = Router::getCurrentRoute();

        $html = '<ul';

        if(isset($data['attributes']))
            foreach($data['attributes'] as $k => $v)
                $html .= sprintf(' %s="%s"', $k, $v);

        $html .= '>';
        
        // Determine count of actual items about to be displayed, this is used to determine
        // the "first" and "last" classes to be added to the current item
        $totalCount = 0;
        foreach($sorted as $route => $routeData)
            if($routeData['hidden'] !== true && !is_null($routeData['title']))
                $totalCount++;

        $currentCount = 1;

        foreach($sorted as $route => $routeData)
        {
            if($routeData['hidden'] === true || is_null($routeData['title']))
                continue;

            $classes = array();

            if(String::startsWith($currentRoute['route'], $route))
                $classes[] = 'active';

            if($currentCount == 1)
                $classes[] = 'first';

            if($currentCount == $totalCount)
                $classes[] = 'last';

            $html .= '<li';
            if($classes)
                $html .= ' class="' . implode(' ', $classes) . '"';
            $html .= '>';

            $html .= '<a href="' . Url::to($route) . '">';

            if(is_string($routeData['title']))
                $html .= $routeData['title'];
            else
                $html .= call_user_func($routeData['title'], 1); // TODO Parameters

            $html .= '</a>';

            if($routeData['children'])
                $html .= self::_getHtml($routeData['children'], $data);

            $html .= '</li>';

            $currentCount++;
        }

        $html .= '</ul>';

        return $html;
    }

    private static function _getSorted()
    {
        if(!self::$_sorted)
        {
            $routes = Router::getRoutes();

            foreach($routes as $route => $routeData)
            {
                // Ignore paths without a title
                /*
                if(!isset($routeData['title']) || is_null($routeData['title']))
                    continue;

                // Ignore paths that are set as hidden
                if(isset($routeData['hidden']) && $routeData['hidden'] === true)
                    continue;
                */

                $ref =& self::$_sorted;
                $routeBits = explode('/', $route);
                $pathBits = array();

                while($routeBits)
                {
                    $bit = array_shift($routeBits);
                    $pathBits[] = $bit;
                    $path = implode('/', $pathBits);

                    if(!isset($ref[$path]))
                    {
                        // For parent routes that dont exist, set a null values
                        $ref[$path] = array(
                            'title' => null,
                            'hidden' => false,
                            'children' => array()
                        );

                        if($path == $route)
                        {
                            /*
                            if(isset($routeData['title']))
                                $ref[$path]['title'] = $routeData['title'];
                            else
                                die(sprintf('The route "%s" doesnt have a title.', $route));
                            */

                            if(isset($routeData['title']))
                                $ref[$path]['title'] = $routeData['title'];

                            if(isset($routeData['hidden']))
                                $ref[$path]['hidden'] = $routeData['hidden'];
                        }
                    }
                    
                    $ref =& $ref[$path]['children'];
                }
            }
        }

        //die(print_r(self::$_sorted, true));

        return self::$_sorted;
    }

}
