<?php

class Validate_Min {

    public static function check($data, $length)
    {
        if(strlen(trim($data)) < $length)
        {
            Validate::setError(sprintf(Config::get('validate.min_error'), $length));
            return false;
        }

        return true;
    }

}
