<?php
final class Path_Events extends Path {

    public static function caffeine_event_priority() {
        return array('caffeine_init' => 10);
    }
    
    public static function caffeine_init()
    {
        Caffeine::trigger('Path', 'callbacks');

		$current_path = Router::current_path();
		if(!strlen($current_path))
			$current_path = PATH_DEFAULT;

		$path_data = self::_auth_path($current_path);

		if($path_data)
			self::_call_path($path_data);
    }

}
