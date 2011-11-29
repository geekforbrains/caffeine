<?php

class Validate_Matches {

    public static function check($fieldName, $fieldTitle, $fieldValue, $compareField)
    {
        // "Field" does not match "Field"
        Dev::debug('validate', sprintf('comparing field %s to %s', $fieldName, $compareField));
    }

}
