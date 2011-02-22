<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * User Configurations
 * =============================================================================
 */
define('USER_LOGIN_REDIRECT', 'admin');
define('USER_LOGOUT_REDIRECT', 'admin/login');

define('USER_CREATE_ROOT', true);
define('USER_ROOT_USERNAME', 'root');
define('USER_ROOT_PASS', 'root');
define('USER_ROOT_EMAIL', 'root@localhost');

define('USER_TIMEOUT', 15); // Specify how many minutes before user session times out

/**
 * =============================================================================
 * User Constants
 * =============================================================================
 */
define('USER_ROOT_SITE_ID', 1);
define('USER_ROOT_SITE', '/');
define('USER_ROOT_ID', 1);  
