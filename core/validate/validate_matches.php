<?php

class Validate_Matches {

    public static function check($fieldName, $fieldValue, $compareFieldName)
    {
        if($fieldValue != $_POST[$compareFieldName])
           Validate::setError($fieldName, 'Does not match other field');
    }

}
