<?php
/**
 * =============================================================================
 * Debug
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class Debug {

	private static $_logs = array();
	private static $_watch = array();
	private static $_ignore = array();

	public static function log($class, $message)
	{
		if(!DEBUG_ENABLED)
			return;
			
		$timestamp = time();
		$args = func_get_args();
		
		if(func_num_args() >= 2)
		{
			$class = array_shift($args);
			$message = array_shift($args);
			
			// Check if using watch list, if we are ignore any classes not in list
			if(self::$_watch &&
				!in_array(strtolower($class), self::$_watch))
				return;
			
			// Check if class is in ignore list
			if(in_array(strtolower($class), self::$_ignore))
				return;
			
			if(count($args) >= 1)
				$message = call_user_func_array('sprintf', 
					array_merge(array($message), $args));
					
			self::$_logs[] = array(
				'timestamp' => $timestamp,
				'class' => $class,
				'message' => $message
			);
		}
	}

	public static function display() {
		if(DEBUG_ENABLED)
			View::load('Debug', 'logs', array('logs' => self::$_logs));
	}

	public static function parse_configs()
	{
		if(DEBUG_WATCH)
		{
			self::$_watch = explode(',', strtolower(DEBUG_WATCH));
			foreach(self::$_watch as $k => $v)
				self::$_watch[$k] = trim($v);
		}

		elseif(DEBUG_IGNORE)
		{
			self::$_ignore = explode(',', strtolower(DEBUG_IGNORE));
			foreach(self::$_ignore as $k => $v)
				self::$_ignore[$k] = trim($v);
		}
	}

}
