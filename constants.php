<?php
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
define('CAFFEINE_FILES_DIR', 'files/');
define('CAFFEINE_FILES_PATH', CAFFEINE_ROOT . CAFFEINE_FILES_DIR);
define('CAFFEINE_MODULES_DIR', 'modules/');
define('CAFFEINE_MODULES_PATH', CAFFEINE_ROOT . CAFFEINE_MODULES_DIR);
define('CAFFEINE_SITES_DIR', 'sites/');
define('CAFFEINE_SITES_PATH', CAFFEINE_ROOT . CAFFEINE_SITES_DIR);
define('CAFFEINE_VERSION', '0.1');
define('CAFFEINE_EXT', '.php');
