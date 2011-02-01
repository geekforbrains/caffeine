<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Database_Events
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
final class Database_Events {

    /**
     * -------------------------------------------------------------------------
     * Implements the Caffeine::event_priority event.
     * -------------------------------------------------------------------------
     */
    public static function caffeine_event_priority() {
        return array('caffeine_init' => 1);
    }

    /**
     * -------------------------------------------------------------------------
     * Implements the Caffeine::bootstrap event.
     *
     * Establishes a connection via the database driver specified in the config
     * file. After the connection is established, other areas of the application
     * have a chance to run "install" methods for creating database tables.
     * -------------------------------------------------------------------------
     */
    public static function caffeine_bootstrap() {
        Database::connect();
    }
    
    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
    public static function caffeine_init()
    {
        if(DATABASE_RUN_INSTALL)
            Caffeine::trigger('Database', 'install');
    }
    
}
