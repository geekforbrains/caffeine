<?php

class Validate extends Module {

    private static $_messageSet = false;
    private static $_errors = array();

    public static function errors() {
        return self::$_errors;
    }

    public static function error($field)
    {
        if(isset(self::$_errors[$field]))
            return self::$_errors[$field];

        return new Validate_Error(); // Return empty object
    }

    public static function passed() {
        return empty(self::$_errors);
    }

    public static function check($field, $checks, $data = null)
    {
        if(is_null($data))
            $data = Input::post($field);

        foreach($checks as $check)
        {
            $params = array();

            if(strstr($check, ':'))
            {
                $bits = explode(':', $check);
                $check = $bits[0];
                $params = array_slice($bits, 1);
            }

            $class = sprintf('Validate_%s', ucfirst($check));

            if(!class_exists($class))
            {
                Log::error('validate', 'Check doesnt exist: ' . $check);
                return false;
            }

            array_unshift($params, $data);
            $resp = call_user_func_array(array($class, 'check'), $params);

            if($resp !== true)
            {
                self::$_errors[$field] = new Validate_Error(
                    Config::get('validate.error_class'),
                    $resp
                );
                
                self::_setMessage();
                return false;
            }
        }

        return true;
    }

    private static function _setMessage()
    {
        if(!self::$_messageSet)
        {
            self::$_messageSet = true;
            Message::error(Config::get('validate.error_message'));
        }
    }

}
