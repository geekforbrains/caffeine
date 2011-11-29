<?php

class Validate extends Module {

    private static $_errors = array();

    public static function setError($field, $message) {
        Message::error($message);
        self::$_errors[$field] = $message;
    }

    public static function passed()
    {
        if(!self::$_errors)
            return true;

        Message::error('Form errors');
        return false;
    }

    /**
     * Calls a corresponding class based on the validation option for the given posted
     * field.
     */
    public static function check($fieldName, $fieldTitle, $validation)
    {
        $fieldValue = isset($_POST[$fieldName]) ? $_POST[$fieldName] : null;

        foreach($validation as $v)
        {
            $bits = explode(':', $v);
            $class = array_shift($bits);

            $params = array_merge(array($fieldName, $fieldTitle, $fieldValue), $bits);

            call_user_func_array(array(sprintf('Validate_%s', ucfirst($class)), 'check'), $params);
        }
    }

}
