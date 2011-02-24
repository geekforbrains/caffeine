<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Path
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 *
 * The Path library is used to call class methods based on URL segments. When
 * a page is loaded, the Path library checks if that path has a registered
 * callback, if it does, the registered class and method are called.
 *
 * @event callbacks
 *      Used to specify a URL path and associated data such as its visibility,
 *      callback and title. Paths can contain wild card values, that will be
 *      passed to the callback method. Wild cards can be "%s", "%d" or "%". They
 *      specify to allow only letters, numbers or any character respectively. 
 *
 *      Example:
 *          return array(
 *              'my/path/%[s|d]' => array(
 *                  'title' => 'My Path',
 *					'alias' => 'load/this/path/instead',
 *                  'callback' => array('MyClass', 'my_method'),
 *                  'visible' => boolean,
 *					'auth' => 'permission name'
 *              )
 *          );
 * =============================================================================
 */
class Path {

	// Stores loaded paths and metadata
	protected static $_current	= null;
    protected static $_paths 	= array(
		'front' => array(
			'title' => null,
			'callback' => array('Path', 'front'),
			'auth' => true,
			'visible' => false
		)
	);

	public static function front() {
		View::load('Path', 'front');
	}

	/**
	 * -------------------------------------------------------------------------
	 * Returns the current path to be called. This is not the same as the path
	 * returned by the Router::current_path().
	 * -------------------------------------------------------------------------
	 */
	public static function get_calling_path() {
		return self::$_current;
	}

	public static function get_paths() {
		return self::$_paths;
	}

	// Deprecated
	public static function current() {
		return self::get_calling_path();
	}

	// Deprecated
	public static function paths() {
		return self::get_paths();
	}

	/**
	 * -------------------------------------------------------------------------
	 * Checks if the given path is equal to or within the current path. Used
	 * to determine active menu items mostly.
	 *
	 * @param $path
	 *		The path to compare to the current path.
	 *
	 * @return boolean
	 *		Returns true if the path is equal to or exists within the current
	 *		path. False otherwise.
	 * -------------------------------------------------------------------------
	 */
	public static function is_active($path) 
	{
		if(preg_match('@' .$path. '@', Router::current_path()))
			return true;
		return false;
	}

	public static function is_front() 
	{
		if(!strlen(Router::current_path()))
			return true;
		return false;
	}

	public static function get_data($path) 
	{
		if(!strlen($path))
			$path = PATH_DEFAULT;

		if(isset(self::$_paths[$path]['alias']))
			$path = self::$_paths[$path]['alias'];

		self::$_current = $path;
		Debug::log('Path', 'Getting callback data for path: %s', $path);

		// First attempt direct path to callback
		if(isset(self::$_paths[$path]))
			return self::$_paths[$path];
		
		// If no direct path, attempt regex callback
		foreach(self::$_paths as $p => $d)
		{
			// Replace wildcards with regex patterns
			$regex = str_replace('%', '(.*?)',  
				str_replace('%d', '([0-9]+)', 
				str_replace('%s', '([A-Za-z\-]+)', $p)));
			
			// Check if regex formatted path matches given path
			if(preg_match('@^' .$regex. '$@', $path, $matches))
			{
				$path_data = self::$_paths[$p];
				$path_data['params'] = array_slice($matches, 1);
				return $path_data;
			}
		}

		Debug::log('Path', 'No callback data found for path: %s', $path);
		return false;
	}

	/**
	 * ------------------------------------------------------------------------
	 * Callback for the Path::callbacks event.
	 * ------------------------------------------------------------------------
	 */
    public static function callback_callbacks($class, $data) 
    {
        foreach($data as $p => $d)
        {
			// Any paths with wildcards cannot by visible items because
			// they are dynamic
            if(strstr($p, '%'))
                $d['visible'] = false;
                
			// Default unset visibility to true
            if(!isset($d['visible']))
                $d['visible'] = true;

			// Default unset permission to false
			if(!isset($d['auth']))
				$d['auth'] = false;

			// If no title set, set to null
			if(!isset($d['title']))
				$d['title'] = null;
                
            self::$_paths[$p] = $d;
        }
    } 


	/**
	 * -------------------------------------------------------------------------
	 * Checks if the user has access to the current path. If not, we either
	 * redirect them to a new configurable path, or show an access denied view.
	 * -------------------------------------------------------------------------
	 */
	protected static function _auth_path($current_path, $path_data)
	{
		if(!Auth::check_access($current_path, $path_data))
		{
			if($current_path == PATH_ACCESS_DENIED_REDIRECT)
				die('You\'re trying to redirect access denied to a page that 
				doesn\'t have anonymous access. This is causing an infinite loop.
				Please modify the PATH_ACCESS_DENIED_REDIRECT configuration in 
				path_config.php or modify the path callback for "' .$current_path. '" 
				to allow anonymous access.');

			return false;
		}

		return $path_data;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Checks the current path against registered callbacks. If a callback is
	 * found for the path, it is called. Otherwise, a 404 page is displayed.
	 *
	 * When calling a callback, if boolean false is returned, the default 404
	 * page is displayed. This allows other modules to attempt to load a page
	 * dynamically, and decide wether to show a 404 or not.
	 * -------------------------------------------------------------------------
	 */
    protected static function _call_path($path_data)
    {
		Debug::log('Path', 'Calling path callback: %s::%s',
			$path_data['callback'][0], $path_data['callback'][1]);

		if(isset($path_data['params']))
			$success = call_user_func_array($path_data['callback'], $path_data['params']);
		else
			$success = call_user_func($path_data['callback']);

		// Method can return boolean false to handle dynamic 404's
		if($success !== false)
		{
			if(isset($path_data['title']) && strlen($path_data['title']))
				View::set_title($path_data['title']);
			return true;
		}

		return false;
    }

}
