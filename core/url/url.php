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
            self::$_host = $_SERVER['HTTP_HOST'];

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
            self::$_base = (isset($bits[0])) ? $bits[0] : '/';

            // If language code is set, force all urls to use it
            if($lang = Multilanguage::getCurrentLang())
                self::$_base .= $lang->code . '/';

            Dev::debug('url', 'Setting base URL: ' . self::$_base);
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

        return self::base() . trim(self::$_current, '/');
    }

    /**
     * Determines if the given path is the same as the current url.
     *
     * @param string $path The path to compare to current url
     * @return boolean True if the given path matches the current url, false othwerise
     */
    public static function isCurrent($path)
    {
        if(self::to($path) == self::current())
            return true;
        return false;
    }

    /**
     * Shorthand method for Url::isCurrent('/')
     */
    public static function isIndex() {
        return self::isCurrent('/');
    }

    /**
     * Redirects to the given path. If the path is relative (doesnt contain http://) its assumed
     * to be within the applications base url. Otherwise it'll redirect to the full url given.
     *
     * @param string $path The relative or full url to redirect to.
     */
    public static function redirect($path)
    {
        if(substr($path, 0, 4) != 'http')
            $path = self::to($path);

        header(sprintf('Location: %s', $path));
        exit(); // Ensures redirect happend right away, no other part of the application is loaded
    }

    /**
     * Returns the relative url to the given path. Mostly used in creating "a" tags. If a full url is
     * given it returns the full url un-touched.
     *
     * @param string $path The path to get a relative url for. Should not have leading or trailing slashes 
     * @param boolean $fullUrl If set to true, converts relative paths to the full application URL
     * @return string URL to the given path
     */
    public static function to($path, $fullUrl = false)
    {
        if(substr($path, 0, 4) == 'http') // Ignore full urls
            return $path;

        $path = self::base() . trim($path, '/');

        if($fullUrl)
            $path = 'http://' . self::host() . $path;

        return $path;
    }

}
