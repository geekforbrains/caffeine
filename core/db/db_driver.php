<?php

/**
 * Loads a driver from the drivers/ dir based on configs. 
 * That class is then extended by the installer, updater and seeder
 *
 * TODO Add more driver support (postgre, sqlite etc.)
 */
if(Config::get('db.driver'))
    require_once(ROOT . 'core/db/drivers/mysql' . EXT);
else
    die('Invalid database driver "' . Config::get('db.driver') . '" in your setup.php file.');
