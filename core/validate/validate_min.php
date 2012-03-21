<?php

class Validate_Min {

    public static function check($fieldName, $fieldValue, $minCount)
    {
        if((is_array($fieldValue) && count($fileValue) < $minCount) || strlen($fieldValue) < $minCount)
        {
            Validate::setError($fieldName, sprintf('Must be a minimum of %d characters', $minCount));
            return false;
        }

        return true;
    }

}
