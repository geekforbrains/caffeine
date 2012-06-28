<?php

class Validate_Required {

    public static function check($data)
    {
        if(is_null($data) || (is_array($data) && empty($data)) || (is_string($data) && !strlen(trim($data))))
            return Config::get('validate.required_error');

        return true;
    }

}
