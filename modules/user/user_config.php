<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * User Configurations
 * =============================================================================
 */
define('USER_LOGIN_REDIRECT', 'admin');
define('USER_LOGOUT_REDIRECT', 'admin/login');

define('USER_TIMEOUT', 15); // Specify how many minutes before user session times out
define('USER_AUTOCREATE_SITES', true);

// Login information for super root
// This user always has access to everything, mostly for maintenance etc.
define('USER_ROOT_USERNAME', 'root');
define('USER_ROOT_PASS', 'root');
define('USER_ROOT_EMAIL', 'root@localhost');

/**
 * =============================================================================
 * User Constants
 * =============================================================================
 */
define('USER_ROOT_SITE', '/');
define('USER_TYPE', 'user');
define('USER_TYPE_SITE', 'user_site');
