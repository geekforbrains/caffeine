<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Message
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class Message {

	// TODO
    private static $_messages = array();

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function store($type, $message) {
        $_SESSION['messages'][$type][] = $message;
    }

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function set($type, $message) {
        self::$_messages[$type][] = $message;
    }
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function get($type) 
    {
        if(isset(self::$_messages[$type]))
            return self::$_messages[$type];
        return false;
    }
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function display()
    { 
        View::load('Message', 'messages', 
            array('messages' => self::$_messages));
    }
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    protected static function _move_stored()
    {
        if(isset($_SESSION['messages']))
            self::$_messages = $_SESSION['messages'];
            
        $_SESSION['messages'] = array();
    }

}
