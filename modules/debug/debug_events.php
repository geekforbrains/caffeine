<?php
final class Debug_Events {

	/**
	 * -------------------------------------------------------------------------
	 * Implements the Caffeine::event_priority event.
	 * -------------------------------------------------------------------------
	 */
	public static function caffeine_event_priority() 
	{
		return array(
			'caffeine_cleanup' => 10,
		);
	}

	public static function caffeine_bootstrap() {
		Debug::parse_configs();
	}

	public static function caffeine_cleanup() {
		if(DEBUG_ENABLED)
			Debug::display();
	}

	public static function view_block_paths() {
		return array(
			'Debug' => CAFFEINE_MODULES_PATH . 'debug/blocks/'
		);
	}

}
