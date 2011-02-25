<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * View_Events
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
final class View_Events {
    
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
	 * Sets the initial default theme. The theme can later be changed by 
	 * implementing the View::change_theme event or by calling the View::theme
	 * method directly.
     * -------------------------------------------------------------------------
     */
    public static function caffeine_bootstrap() {
        View::theme(VIEW_DEFAULT_THEME);
    }
    
    /**
     * -------------------------------------------------------------------------
     * Implements the Caffeine::init event. 
     * -------------------------------------------------------------------------
     */
    public static function caffeine_init() 
	{
		Caffeine::trigger('View', 'change_theme');
        //Caffeine::trigger('View', 'block_paths');
    }
   
    /**
     * -------------------------------------------------------------------------
     * Implements the Caffeine::cleanup event.
	 *
	 * Outputs any rendered HTML to the browser. This method is always called
	 * last.
     * -------------------------------------------------------------------------
     */
    public static function caffeine_cleanup() {
        View::output();
    }
    
}
