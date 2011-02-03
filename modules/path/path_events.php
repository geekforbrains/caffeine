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

		$path_data = self::_auth_path($current_path);

		if($path_data && self::_call_path($path_data))
			return;

		View::load('Path', '404');
    }

}
