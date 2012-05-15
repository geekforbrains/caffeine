<?php

class Validate_Required {

    public static function check($data)
    {
        if(!strlen(trim($data)))
        {
            Validate::setError(Config::get('validate.required_error'));
            return false;
        }

        return true;
    }

}
