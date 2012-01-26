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


    /**
     * Used by child validation classes to set an error message for the checked field.
     */
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
    public static function check($fieldName, $validation, $return = false)
    {
        $cleanName = str_replace('[]', '', $fieldName);
        $fieldValue = isset($_POST[$cleanName]) ? $_POST[$cleanName] : null;

        foreach($validation as $v)
        {
            $bits = explode(':', $v);
            $class = array_shift($bits);

            $params = array_merge(array($fieldName, $fieldValue), $bits);

            if(!$response = call_user_func_array(array(sprintf('Validate_%s', ucfirst($class)), 'check'), $params))
            {
                if($return)
                    return self::$_errors[$cleanName];
                break;
            }
            
            /*
            if(!call_user_func_array(array(sprintf('Validate_%s', ucfirst($class)), 'check'), $params))
                break;
            */
        }

        if($return)
            return null;
    }


    public static function returnCheck($fieldName, $validation) {
        return self::check($fieldName, $validation, true);
    }


}
