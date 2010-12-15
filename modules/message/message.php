<?php
/**
 * =============================================================================
 * Message
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class Message {

    private static $_messages = array();

    public static function store($type, $message) {
        $_SESSION['messages'][$type][] = $message;
    }

    public static function set($type, $message) {
        self::$_messages[$type][] = $message;
    }
    
    public static function get($type) 
    {
        if(isset(self::$_messages[$type]))
            return self::$_messages[$type];
        return false;
    }
    
    public static function display()
    { 
        View::load('Message', 'messages', 
            array('messages' => self::$_messages));
    }
    
    protected static function _move_stored()
    {
        if(isset($_SESSION['messages']))
            self::$_messages = $_SESSION['messages'];
            
        $_SESSION['messages'] = array();
    }

}
