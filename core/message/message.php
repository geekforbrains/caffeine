<?php

class Message extends Module {

    /**
     * Stores a message to be displayed at next page load. This can either be after a form post or
     * redirect for example. The type specifies the type of message and will act as the css class for styling
     *
     * @param string $type The type of message, will be used as a CSS class for styling
     * @param string $msg The message to display
     */
    public static function set($type, $msg)
    {
        if(!isset($_SESSION['messages']))
            $_SESSION['messages'] = array();

        $_SESSION['messages'][$type][] = $msg;
    }

    /**
     * Checks if any messages have been registered. If messages are found they are returned
     * to the caller and cleared from the session. Messages are only displayed once.
     */
    public static function get()
    {
        $messages = array();

        if(isset($_SESSION['messages']))
        {
            $messages = $_SESSION['messages'];
            unset($_SESSION['messages']);
        }

        return $messages;
    }

    /**
     * Shorthand method for setting "ok" messages.
     */
    public static function ok($message) {
        self::set('ok', $message);
    }

    /**
     * Shorthand method for setting "error" messages.
     */
    public static function error($message) {
        self::set('error', $message);
    }

    /**
     * Shorthand method for setting "info" messages.
     */
    public static function info($message) {
        self::set('info', $message);
    }

}
