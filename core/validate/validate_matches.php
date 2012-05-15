<?php

class Validate_Matches {

    public static function check($data, $compareField)
    {
        if($data != Input::post($compareField))
        {
            Validate::setError(Config::get('validate.matches_error'));
            return false;
        }

        return true;
    }

}
