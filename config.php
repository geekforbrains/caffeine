<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Caffeine Configurations
 *
 * This file can be overridden by placing it within a "sites" directory.
 * =============================================================================
 */
define('CAFFEINE_IGNORE_MODULES', ''); // A comma seperated list of modules to ignore when scanning
define('CAFFEINE_TIMEZONE', 'UTC'); // Default UTC
define('CAFFEINE_DEBUG', true);
define('CAFFEINE_DEBUG_VERBOSITY', 3); // 1 = Low, 2 = Medium, 3 = High
define('CAFFEINE_DEBUG_IGNORE', ''); // A list of comma seperated classes to ignore in debug
define('CAFFEINE_DEBUG_WATCH', ''); // A list of comma seperated classes to watch, overrides ignore
define('CAFFEINE_ERROR_REPORTING', E_ALL | E_STRICT);
