<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Path_Events
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
final class Path_Events extends Path {

	/**
	 * -------------------------------------------------------------------------
	 * Implements the Caffeine::event_priority event.
	 * -------------------------------------------------------------------------
	 */
    public static function caffeine_event_priority() {
        return array('caffeine_init' => 10);
    }
    
	/**
	 * -------------------------------------------------------------------------
	 * Implements the Caffeine::init event.
	 * -------------------------------------------------------------------------
	 */
    public static function caffeine_init()
    {
        Caffeine::trigger('Path', 'callbacks');

		$current_path = Router::current_path();
		if(!strlen($current_path))
		{
			if(strlen(PATH_DEFAULT))
				$current_path = PATH_DEFAULT;
			else
				die('The default path configuration cannot be empty.');
		}

		if($path_data = Path::get_data($current_path))
		{
			if(self::_auth_path($current_path, $path_data))
			{
				if(self::_call_path($path_data))
					return;
			}
			else
			{
				// If user is logged in, show them access denied page
				if(User::get('cid') > 0)
					View::load('Path', 'access_denied', array(), false);

				// Otherwise, if user isn't logged in, redirect to login page
				// with error
				else
				{
					//Message::store(MSG_ERR, 'Access Denied. Please login.');
					Router::redirect(PATH_ACCESS_DENIED_REDIRECT);
				}

				return;
			}
		}

		// If we got here either something went wrong or the path doesn't exist
		// Set 404 header and display 404 view
		header('HTTP/1.0 404 Not Found');
		//View::set_title('404: Page Not Found');
		View::load('Path', '404', array(), false);
    }

}
