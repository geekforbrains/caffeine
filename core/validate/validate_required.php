<?php

class Validate_Required {

    public static function check($fieldName, $fieldTitle, $fieldValue)
    {
        Dev::debug('validate', 'checking that field exists.');

        if(is_array($fieldValue) && count($fieldValue) <= 0 || !strlen($fieldValue))
            Validate::setError($fieldName, sprintf('%s is required', $fieldTitle));
    }

}
