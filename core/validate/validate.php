<?php

class Validate extends Module {

    private static $_lastError = null;
    private static $_errors = array();

    public static function setError($message)
    {
        self::$_lastError = $message;
        self::$_errors[] = $message;
    }

    public static function getLastError() {
        return self::$_lastError;
    }

    public static function getErrors() {
        return self::$_errors;
    }

    public static function check($data, $checks)
    {
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

            if(!call_user_func_array(array($class, 'check'), $params))
                return false;
        }

        return true;
    }

}
