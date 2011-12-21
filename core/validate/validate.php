<?php

class Validate extends Module {

    private static $_errors = array();

    /**
     * Gets an error for a given field. If no error is set, null is returned.
     */
    public static function error($field)
    {
        if(isset(self::$_errors[$field]))
            return sprintf('<div class="error"><span>%s</span></div>', self::$_errors[$field]);
        return null;
    }

    public static function setError($field, $message) {
        self::$_errors[$field] = $message;
    }

    public static function passed()
    {
        if(!self::$_errors)
            return true;

        Message::error('Missing or invalid fields.');
        return false;
    }

    /**
     * Calls a corresponding class based on the validation option for the given posted
     * field.
     */
    public static function check($fieldName, $validation)
    {
        $fieldValue = isset($_POST[$fieldName]) ? $_POST[$fieldName] : null;

        foreach($validation as $v)
        {
            $bits = explode(':', $v);
            $class = array_shift($bits);

            $params = array_merge(array($fieldName, $fieldValue), $bits);

            if(!call_user_func_array(array(sprintf('Validate_%s', ucfirst($class)), 'check'), $params))
                break;
        }
    }

}
