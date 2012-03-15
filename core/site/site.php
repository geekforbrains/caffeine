<?php

class Site extends Module {
    
    /**
     * Stores the relative path from ROOT to the current site.
     */
    private static $_sitePath = null;

    /**
     * @return string Full path to current site directory.
     */
    public static function getPath() {
        return ROOT . self::getRelativePath();
    }

    /**
     * @return string The relative path from ROOT to the current site directory.
     */
    public static function getRelativePath()
    {
        if(is_null(self::$_sitePath))
        {
            if(!IS_CLI) // Sites not supported in CLI mode
            {
                $path = sprintf('sites/%s/', $_SERVER['HTTP_HOST']);

                if(file_exists(ROOT . $path))
                    self::$_sitePath = $path;
            }

            if(is_null(self::$_sitePath))
                self::$_sitePath = 'sites/default/';
        }

        return self::$_sitePath;
    }

}
