<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Router
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 *
 * The Router library is used to provide information about the URL. It parses
 * out segments and allows the application to make use of them as well as
 * providing easy ways for constructing URL's and redirecting.
 *
 * NOTE: Trailing slashes are ALWAYS stripped from URL's.
 *
 * @event modify_segments
 *      Allows modification of parsed segments before finishing.
 * =============================================================================
 */
class Router {

	protected static $_http		= 'http://';
	protected static $_host		= null;
    protected static $_path     = ''; // Stores current path
    protected static $_segments = array(); // Stores parsed segments
    protected static $_base     = null; // Stores the applications base URL
    
    /**
     * -------------------------------------------------------------------------
     * Returns the current URL path. This does NOT include the base URL.
     * -------------------------------------------------------------------------
     */
    public static function current_path() {
        return self::$_path;
    }
    
    /**
     * -------------------------------------------------------------------------
     * Returns the full current URL.
     * -------------------------------------------------------------------------
     */
    public static function current_url() {
        return self::url(self::$_path);
    }
    
    /**
     * -------------------------------------------------------------------------
     * Returns the full base URL.
     * -------------------------------------------------------------------------
     */
    public static function base() {
        return self::$_base;
    }

    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
	public static function subdomain($tld_count = ROUTER_TLD_COUNT) 
	{
		$host_bits = explode('.', 
			str_ireplace('www.', '', $_SERVER['HTTP_HOST']), 2); 

		if(substr_count($host_bits[1], '.') == $tld_count)
			return $host_bits[0];

		return null;
	}
    
    /**
     * -------------------------------------------------------------------------
     * Combines a given path with the base path and returns the full URL. This
     * allows an application to use full URL paths without being dependant on 
     * a specific domain.
     *
     * @param $path
     *      The path you want combined with the base URL.
     *
     * @param $args
     *      An array of arguments to be replaced in the URL. Check PHP's
     *      sprintf function for syntax.
     *
     * @return
     *      Returns a full URL of the base path and given path combined.
     */
    public static function url() 
    {
        $args = func_get_args();

        return sprintf('%s/%s', self::$_base, 
            rtrim(call_user_func_array('sprintf', $args), '/'));
    }

	public static function secure_url()
	{
		$args = func_get_args();
		$url = call_user_func_array(array('self', 'url'), $args);
		return 'https://' . str_replace('www.', '', self::$_host) . $url;
	}

	public static function full_url()
	{
		$args = func_get_args();
		$url = call_user_func_array(array('self', 'url'), $args);
		return self::$_http . self::$_host . $url;
	}
    
    /**
     * -------------------------------------------------------------------------
     * Gets the value of the given segment number. Segment numbers are based on
     * an array, so the first value is always 0.
     *
     * @param $num
     *      The segment number you want a value for.
     *
     * @return
     *      Returns the segment value if it exists, false otherwise.
     * -------------------------------------------------------------------------
     */
    public static function segment($num) 
    {
        if(isset(self::$_segments[$num]))
            return self::$_segments[$num];
        return false;
    }
    
    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
    public static function segments() {
        return self::$_segments;
    }
    
    /**
     * -------------------------------------------------------------------------
     * Redirects the page to a given path or URL. If the given path doesn't
     * start with 'http', its assumed an application path and is combined with
     * the base URL.
     *
     * @param $path
     *      The full URL or application path you want to be redirected to.
     * -------------------------------------------------------------------------
     */
    public static function redirect($path) 
    {
        if(substr(strtolower($path), 0, 4) != 'http')
            $path = self::url($path);
            
        header('Location: ' . $path);
    }

    /**
     * -------------------------------------------------------------------------
     * Parses segments out of the URL and provides them to the application
     * through events for use.
     * -------------------------------------------------------------------------
     */
    protected static function _parse_segments()
    {
        Debug::log('Router', 'Parsing URL segments');
        $host = $_SERVER['HTTP_HOST'];
        $bits = explode('index.php', $_SERVER['SCRIPT_NAME']);
        $dir = '';
               
        if(isset($bits[0]))
            $dir = $bits[0];

        //self::$_base = rtrim($http . $host . $dir, '/');
		self::$_http = (isset($_SERVER['HTTPS'])) ? 'https://' : 'http://';
		self::$_host = $host;
        self::$_base = rtrim($dir, '/'); // Relative
        self::$_path = isset($_GET['q']) ? $_GET['q'] : '';
        
        self::$_segments = strlen(self::$_path) ? explode('/', self::$_path) : array();
    }

}

// Shorthand function for Router::url
function l($path) {
	$args = func_get_args();
	echo call_user_func_array(array('Router', 'url'), $args);
}

// Shorthand function for Router::secure_url
function sl($path) {
	$args = func_get_args();
	echo call_user_func_array(array('Router', 'secure_url'), $args);
}

// Shorthand function for Router::full_url
function fl($path) {
	$args = func_get_args();
	echo call_user_func_array(array('Router', 'full_url'), $args);
}
