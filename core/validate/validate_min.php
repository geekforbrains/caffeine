<?php

class Validate_Min {

    public static function check($data, $length)
    {
        if(strlen(trim($data)) < $length)
            return sprintf(Config::get('validate.min_error'), $length);

        return true;
    }

}
