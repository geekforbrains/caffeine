<?php
/**
 * =============================================================================
 * Caffeine Configurations
 * =============================================================================
 */
define('CAFFEINE_TIMEZONE', 'UTC'); // Default UTC
define('CAFFEINE_DEBUG', true);
define('CAFFEINE_DEBUG_VERBOSITY', 3); // 1 = Low, 2 = Medium, 3 = High
define('CAFFEINE_DEBUG_IGNORE', 'caffeine'); // A list of comma seperated classes to ignore in debug
define('CAFFEINE_DEBUG_WATCH', ''); // A list of comma seperated classes to watch, overrides ignore
define('CAFFEINE_ERROR_REPORTING', E_ALL | E_STRICT);

/**
 * =============================================================================
 * Caffeine Constants
 * Don't edit these unless you know what you're doing.
 * =============================================================================
 */
define('CAFFEINE_DEFAULT_EVENT_PRIORITY', 5);
define('CAFFEINE_EVENT_CALLBACK', 'callback_%s');
define('CAFFEINE_EVENTS_FILE_FORMAT', '%s_events');
define('CAFFEINE_CONFIG_FILE_FORMAT', '%s_config');
 
define('CAFFEINE_ROOT', str_replace('\\', '/', realpath('.')) . '/');
define('CAFFEINE_MODULES_DIR', 'modules/');
define('CAFFEINE_MODULES_PATH', CAFFEINE_ROOT . CAFFEINE_MODULES_DIR);
define('CAFFEINE_SITES_DIR', 'sites/');
define('CAFFEINE_SITES_PATH', CAFFEINE_ROOT . CAFFEINE_SITES_DIR);
define('CAFFEINE_VERSION', '0.1');
define('CAFFEINE_EXT', '.php');
