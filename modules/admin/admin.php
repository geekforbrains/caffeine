<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Admin
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 *
 * The Admin module provides an administration back-end theme that other modules
 * can provide menu items and content blocks for.
 * =============================================================================
 */
class Admin {

	/**
	 * ------------------------------------------------------------------------
	 * Redirects all traffic from the root admin path to the default path
	 * specified in the admin config file
	 *
	 * This allows you to change the default page a user is taken to when they
	 * visit and login to the admin system. 
	 * ------------------------------------------------------------------------
	 */
    public static function redirect() {
        Router::redirect(ADMIN_DEFAULT_PATH);
    }

}
