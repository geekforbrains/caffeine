<?php 

class Url extends Module {

    /**
     * Stores the current scheme.
     */
    private static $_scheme = null;

    /**
     * Stores the current host.
     */
    private static $_host = null;

    /**
     * Stores the application base, including any subdirectories.
     */
    private static $_base = null;

    /**
     * Stores the current relative url.
     */
    private static $_current = null;

    /**
     * Returns the current scheme.
     */
    public static function scheme()
    {
        if(is_null(self::$_scheme))
            self::$_scheme = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';

        return self::$_scheme;
    }

    /**
     * Returns the current host.
     */
    public static function host()
    {
        if(is_null(self::$_host))
            self::$_host - $_SERVER['HTTP_HOST'];

        return self::$_host;
    }

    /**
     * Returns the full url to the base of the application, including any sub-directories.
     */
    public static function base()
    {
        if(is_null(self::$_base))
        {
            $bits = explode('index.php', $_SERVER['SCRIPT_NAME']);
            self::$_base = (isset($bits[0])) ? rtrim($bits[0], '/') : '';
        }

        return self::$_base;
    }

    /**
     * Returns the current relative url without trailing slashes.
     */
    public static function current()
    {
        if(is_null(self::$_current))
        {
            self::$_current = '/';
            if(isset($_GET['r']) && strlen($_GET['r']))
                self::$_current = rtrim($_GET['r'], '/');
        }

        return self::base() . '/' . self::$_current;
    }

    /**
     * Redirects to the given path. If the path is relative (doesnt contain http://) its assumed
     * to be within the applications base url. Otherwise it'll redirect to the full url given.
     */
    public static function redirect($path)
    {
        if(substr($path, 0, 4) != 'http')
            $path = self::to($path);

        header(sprintf('Location: %s', $path));
    }

    /**
     * Returns the relative url to the given path. Mostly used in creating "a" tags.
     *
     * @param string $path The path to get a relative url for. Should not have leading or trailing slashes 
     *
     * @return string Relative url to the given path
     */
    public static function to($path) {
        return self::base() . '/' . $path;
    }

}
