<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Menu_Events
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
final class Menu_Events {

	/**
	 * -------------------------------------------------------------------------
	 * Implements the View::block_paths event.
	 * -------------------------------------------------------------------------
	 */
    public static function view_block_paths() {
        return array('Menu' => CAFFEINE_MODULES_PATH . 'menu/blocks/');
    }

}
