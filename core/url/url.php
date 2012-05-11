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
     * Stores the segments of the current url (does not include base)
     */
    private static $_segments = null;

    /**
     * Stores the current language code, if any.
     */
    private static $_lang = null;

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
            self::$_host = $_SERVER['SERVER_NAME'];

        return self::$_host;
    }

    /**
     * Returns the relative url to the base of the application, including any sub-directories.
     * If a base url exists, it will start with a slash "/", otherwise the base will be an empty string.
     */
    public static function base($includeLang = true)
    {
        if(is_null(self::$_base))
        {
            $bits = explode('index.php', $_SERVER['SCRIPT_NAME']);
            self::$_base = (isset($bits[0])) ? rtrim($bits[0], '/') : '';

            if($lang = Multilanguage::getCurrentLang())
                self::$_lang = $lang->code;
        }

        if($includeLang && !is_null(self::$_lang))
            return self::$_base . '/' . self::$_lang;
        return self::$_base;
    }

    /**
     * Returns the current relative url without trailing slashes. Does NOT include base url.
     * The current url will always start with a slash "/".
     */
    public static function current()
    {
        if(is_null(self::$_current))
        {
            if(isset($_GET['r']) && strlen($_GET['r']))
                self::$_current = $_GET['r'];

            if(Multilanguage::urlHasLangCode(self::$_current))
                self::$_current = substr(self::$_current, 3);

            if(!strlen(self::$_current))
                self::$_current = '/';
            else
                self::$_current = '/' . trim(self::$_current, '/');
        }

        return self::$_current;
    }

    /**
     * Returns the previous url visisted. If no previous url was found, the current url
     * is returned.
     */
    public static function previous($step = 1)
    {
        $history = Input::sessionGet('url.history');
        
        if(isset($history[$step]))
            return $history[$step];

        return Url::current();
    }

    /**
     * Returns all segments of the current URL in an array.
     */
    public static function segments()
    {
        if(is_null(self::$_segments))
        {
            $current = ltrim(self::current(), '/');
            self::$_segments = (strlen($current)) ? explode('/', $current) : array();
        }

        return self::$_segments;
    }

    /**
     * Gets a single segment, if it exists. Otherwise null returned. Segments are array based,
     * so they start at 0.
     */
    public static function segment($num)
    {
        $segments = self::segments();

        if(isset($segments[$num]))
            return $segments[$num];
        return null;
    }

    /**
     * Determines if the given path is the same as the current url.
     *
     * @param string $path The path to compare to current url
     * @return boolean True if the given path matches the current url, false othwerise
     */
    public static function isCurrent($path)
    {
        if(trim($path, '/') == trim(self::current(), '/'))
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
    public static function to($path, $fullUrl = false, $includeLang = true)
    {
        if(substr($path, 0, 4) == 'http') // Ignore full urls
            return $path;

        $path = rtrim(self::base($includeLang) . '/' . ltrim($path, '/'), '/');

        if(!strlen($path))
            $path = '/';

        if($fullUrl)
            $path = 'http://' . self::host() . $path;

        return $path;
    }

    /**
     * Short hand method for getting a relative url, including base, to the current url.
     */
    public static function toCurrent($fullUrl = false) {
        return self::to(self::current(), $fullUrl);
    }

    /**
     * Get url to given language. If language is set to null, the current langauge code, if any, will be removed.
     * If no path is specified, the current url path will be used.
     *
     * @param string $lang The 3 letter language code to convert the url to, if null, removes lang code.
     * @param string $path The url path to prepend $lang code to. If ommited, current url is used.
     * @return string The $path with the given $lang prepended.
     */
    public static function toLang($lang, $path = null)
    {
        if(is_null($path))
            $path = self::current();
        else
            $path = '/' . $path;

        return self::to($lang . $path, false, false);
    }

}
