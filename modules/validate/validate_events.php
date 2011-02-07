<?php
/**
 * =============================================================================
 * Validate_Events
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
final class Validate_Events {

	/**
	 * -------------------------------------------------------------------------
	 * Implements the View::block_paths event.
	 * -------------------------------------------------------------------------
	 */
	public static function view_block_paths() {
		return array('Validate' => CAFFEINE_MODULES_PATH . 'validate/blocks/');
	}

}
