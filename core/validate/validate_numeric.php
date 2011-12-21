<?php

class Validate_Numeric {


    public static function check($fieldName, $fieldValue)
    {
        if(!is_numeric($fieldValue))
        {
            Validate::setError($fieldName, 'Must be a numeric value');
            return false;
        }

        return true;
    }


}
