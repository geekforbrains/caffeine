<?php
/**
 * =============================================================================
 * Database
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 *
 * The Database library is a special kind of library. Because it might be loaded
 * during obscure points of execution, all the required files must be loaded
 * in here. We can't count on any events to be called in a specific order.
 *
 * So here we get the configs and the database driver, set via the config.
 * The database driver is the actual "Database" class. This enough to keep the
 * Caffeine::autoload method happy.
 *
 * @event install
 *      Used by other areas of the application to create tables based on a
 *      schema api.
 * =============================================================================
 */
require_once(CAFFEINE_MODULES_PATH . 'database/drivers/' . DATABASE_DRIVER . CAFFEINE_EXT);
