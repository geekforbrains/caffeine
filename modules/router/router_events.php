<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Router_Events
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
final class Router_Events extends Router {

    /**
     * -------------------------------------------------------------------------
     * Implements the Caffeine::bootstrap event.
     *
     * Used to determine base URL and parse current path.
     * -------------------------------------------------------------------------
     */
    public static function caffeine_bootstrap() {
        self::_parse_segments();
    }

    /**
     * -------------------------------------------------------------------------
     * Implements the Caffeine::init event.
     *
     * Allows others to modify the current path before starting the application.
     * -------------------------------------------------------------------------
     */
    public static function caffeine_init() {
        self::$_segments = Caffeine::trigger('Router', 'modify_segments', 
            self::$_segments);
    }

}
