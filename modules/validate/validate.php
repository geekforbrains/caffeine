<?php 
class Validate {

	private static $_errors = array();

	/**
	 * -------------------------------------------------------------------------
	 * Performs a series of checks on a posted field. Checks must have a method
	 * associated with them that have the same name as the check prepended with
	 * and underscore. Ex: "required" = _required.
	 *
	 * @param $field_name
	 *		The name of the field in the $_POST variable to check.
	 *
	 * @param $display_name
	 *		The display name to use when displaying an error for this field.
	 *
	 * @param $checks
	 *		An array of checks to perform on the given field. 
	 * -------------------------------------------------------------------------
	 */
	public static function check($field_name, $display_name, $checks)
	{	
		foreach($checks as $check)
		{
			Caffeine::debug(1, 'Validate', 'Performing check "%s" on field "%s"',
				$check, $field_name);

			if(!call_user_func(array('self', '_' . $check), 
				$field_name, $display_name))
			{
				Caffeine::debug(3, 'Validate', 'Check "%s" failed for field "%s"', 
					$check, $field_name);

				break;
			}
		}
	}

	// TODO
	private static function _required($f, $d)
	{
		if(!isset($_POST[$f]) || !strlen($_POST[$f]))
		{
			self::$_errors[$f] = sprintf(VALIDATE_REQUIRED, $d); 
			return false;
		}

		return true;
	}

	// TODO
	private static function _email($f, $d)
	{
		$status = isset($_POST[$f]);

		if($status && function_exists('filter_var')) 
		{
      		if(filter_var($_POST[$f], FILTER_VALIDATE_EMAIL) === false)
        		$status = false;
    	} 
		elseif($status) 
		{
			$status = preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $f);
    	}

		if(!$status)
			self::$_errors[$f] = sprintf(VALIDATE_EMAIL, $d);

		return $status;
	}

}
