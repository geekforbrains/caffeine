<?php
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
 *              'my/path/%s' => array(
 *                  'title' => 'My Path',
 *                  'callback' => array('MyClass', 'my_method'),
 *                  'visible' => boolean,
 *					'auth' => 'permission name'
 *              )
 *          );
 * =============================================================================
 */
class Path {

	// Stores loaded paths and metadata
    protected static $_paths 	= array();

	public static function get_paths() {
		return self::$_paths;
	}

	public static function get_path($path) 
	{
		if(!strlen($path))
			$path = PATH_DEFAULT;

		Caffeine::debug(1, 'Path', 'Searching callback data for path: %s', $path);

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
                
            self::$_paths[$p] = $d;
        }
    } 


	/**
	 * -------------------------------------------------------------------------
	 * Checks if the user has access to the current path. If not, we either
	 * redirect them to a new configurable path, or show an access denied view.
	 * -------------------------------------------------------------------------
	 */
	protected static function _auth_path($current_path)
	{
		$path_data = self::get_path($current_path);

		if(!$path_data)
		{
			View::load('Path', '404');
			return false;
		}

		if(!Auth::check_access($current_path, $path_data))
		{
			if($current_path == PATH_ACCESS_DENIED_REDIRECT)
				die('You\'re trying to redirect access denied to a page that 
				doesn\'t have anonymous access. This is causing an infinite loop.
				Please modify the PATH_ACCESS_DENIED_REDIRECT configuration in 
				path_config.php or modify the path callback for "' .$current_path. '" 
				to allow anonymous access.');

			if($_SESSION['user'] > 0)
			{
				View::load('Path', 'access_denied');
				return false;
			}
			else
			{
				Message::store('error', 'Access Denied. Please login.');
				Router::redirect(PATH_ACCESS_DENIED_REDIRECT);
			}
		}

		return $path_data;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Checks the current path against registered callbacks. If a callback is
	 * found for the path, it is called. Otherwise, a 404 page is displayed.
	 * -------------------------------------------------------------------------
	 */
    protected static function _call_path($path_data)
    {
		if($path_data)
		{
			Caffeine::debug(1, 'Path', 'Calling path callback: %s::%s',
				$path_data['callback'][0], $path_data['callback'][1]);
	
			if(isset($path_data['params']))
				call_user_func_array($path_data['callback'], $path_data['params']);
			else
				call_user_func($path_data['callback']);

			return;
		}

		View::load('Path', '404');
    }

}
