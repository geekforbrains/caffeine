<?php

class Input extends Module {

    public static function clean($data)
    {
        if(is_array($data))
        {
            foreach($data as &$d)
                $d = self::clean($d); 

            return $data;
        }

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
