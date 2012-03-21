<?php

class Validate_Required {

    public static function check($fieldName, $fieldValue)
    {
        if((is_array($fieldValue) && count($fieldValue) <= 0) || (!is_array($fieldValue) && !strlen(trim($fieldValue))))
        {
            Validate::setError($fieldName, 'Cannot be empty');
            return false;
        }

        return true;
    }

}
