<?php

class Input extends Module {

    /**
     * Used for getting a posted value from $_POST or a default value
     * if the posted value doesn't exist.
     *
     * Example
     *      echo Input::post('some_filed', 'Default Value');
     *
     * TODO Add XSS filtering
     *
     * @param string $field The field to get within $_POST
     * @param mixed $defaultValue An optional default value to return if $field doesn't exist within $_POST
     * @return mixed Returns the value of $_POST[$field] if it exists, otherwise the value of $defaultValue
     */
    public static function post($field, $defaultValue = null)
    {
        if(isset($_POST[$field]))
            return $_POST[$field];
        return $defaultValue;
    }

    /**
     * TODO  Will be used for working with sessions securely.
     */
    public static function session() {}

    /**
     * TODO Will be used for working with cookies securely.
     */
    public static function cookie() {}

}
