<?php

class Validate extends Module {

    private static $_errors = array();

    public static function setError($field, $message) {
        self::$_errors[$field] = $message;
    }

    public static function passed()
    {
        if(!self::$_errors)
            return true;
        return false;
    }

    /**
     * Calls a corresponding class based on the validation option for the given posted
     * field.
     */
    public static function check($field, $validation)
    {
        // TODO Need a way to get a field title
        // TODO Need a way to get field value, so all we pass is the field name

        foreach($validation as $v)
        {
            $bits = explode(':', $v);
            $validation = array_shift($bits);
            $params = $bits;

            array_unshift($params, $_POST[$field]);
            call_user_func_array(array(sprintf('Validate_%s', ucfirst($validation)), 'check'), $params);
        }
    }

}
