<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Admin_Events
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
final class Admin_Events {

	/**
	 * -------------------------------------------------------------------------
	 * Implements the View::change_theme event.
	 *
	 * Sets all sub-pages of "admin" to use the theme specified in
	 * admin_config.php
	 * -------------------------------------------------------------------------
	 */
    public static function view_change_theme()
    {
        if(Router::segment(0) == 'admin' && View::theme_exists(ADMIN_THEME))
            return array('theme' => ADMIN_THEME);
   	} 
    
	/**
	 * -------------------------------------------------------------------------
	 * Implements the Path::callbacks event.
	 * -------------------------------------------------------------------------
	 */
    public static function path_callbacks() {
        return array(
            'admin' => array(
                'title' => 'Admin',
                'callback' => array('Admin', 'redirect'),
            ),
			'admin/admin' => array(
				'title' => 'Administration',
				'alias' => ADMIN_ADMIN_ALIAS
			)
        );
    }

}
