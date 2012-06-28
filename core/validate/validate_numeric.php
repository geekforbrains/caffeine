<?php

class Validate_Numeric {

    public static function check($data)
    {
        if(!is_numeric($data))
            return Config::get('validate.numeric_error');

        return true;
    }

}
