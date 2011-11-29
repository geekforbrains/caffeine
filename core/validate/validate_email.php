<?php

class Validate_Email {

    public static function check($fieldName, $fieldValue)
    {
        $status = true;

		if(function_exists('filter_var')) 
		{
      		if(filter_var($fieldValue, FILTER_VALIDATE_EMAIL) === false)
        		$status = false;
    	} 
		else
		{
			$status = preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $fieldValue);
    	}

		if(!$status)
            Validate::setError($fieldName, 'Not a valid email address');
    }

}
