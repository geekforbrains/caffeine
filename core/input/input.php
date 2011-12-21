<?php

class Input extends Module {


    public static function post($field, $defaultValue = null)
    {
        if(isset($_POST[$field]))
            return $_POST[$field];
        return $defaultValue;
    }


}
