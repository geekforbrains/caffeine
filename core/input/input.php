<?php

class Input extends Module {

    private static $_put = null;

    /**
     * Returns the HTTP "verb" being used for the current page request.
     */
    public static function action() {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function put($key, $defaultValue = null)
    {
        if(is_null(self::$_put))
            parse_str(file_get_contents('php://input'), self::$_put);

        if(isset(self::$_put[$key]))
            return self::clean(self::$_put[$key]);

        return $defaultValue;
    }

    public static function clean($data)
    {
        if(is_array($data))
        {
            foreach($data as &$d)
                $d = self::clean($d); 

            return $data;
        }

        if(get_magic_quotes_gpc())
            $data = stripslashes($data);

        return strip_tags(htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8')); 
    }

    public static function post($field, $defaultValue = null, $raw = false)
    {
        if(isset($_POST[$field]))
        {
            if($raw)
                return $_POST[$field];

            return self::clean($_POST[$field]);
        }

        return $defaultValue;
    }

    public static function get($field, $defaultValue = null, $raw = false)
    {
        if(isset($_GET[$field]))
        {
            if($raw)
                return $_GET[$field];

            return self::clean($_GET[$field]);
        }

        return $defaultValue;
    }

    public static function sessionSet($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function sessionGet($key, $defaultValue = null)
    {
        if(isset($_SESSION[$key]))
            return self::clean($_SESSION[$key]);
        return $defaultValue;
    }

    public static function sessionDel($key)
    {
        if(isset($_SESSION[$key]))
        {
            unset($_SESSION[$key]);
            return true;
        }

        return false;
    }

    // TODO
    public static function cookieSet() {}
    public static function cookieGet() {}
    public static function cookieDel() {}

}
