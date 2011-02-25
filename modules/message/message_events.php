<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Message_Events
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
final class Message_Events extends Message {

	/**
	 * -------------------------------------------------------------------------
	 * Implements the Caffeine::bootstrap event.
	 * -------------------------------------------------------------------------
	 */
    public static function caffeine_bootstrap() {
        self::_move_stored();
    }

}
