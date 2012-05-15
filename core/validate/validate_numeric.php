<?php

class Validate_Numeric {

    public static function check($data)
    {
        if(!is_numeric($data))
        {
            Validate::setError(Config::get('validate.numeric_error'));
            return false;
        }

        return true;
    }

}
