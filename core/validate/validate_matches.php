<?php

class Validate_Matches {

    public static function check($data, $compareField)
    {
        if($data != Input::post($compareField))
            return Config::get('validate.matches_error');

        return true;
    }

}
