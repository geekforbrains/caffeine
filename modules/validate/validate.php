<?php 
/**
 * =============================================================================
 * Validate
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 *
 * The validate module is used to validate types of content usually submitted
 * via a form. Different "checks" can be made on any one peice of content.
 * =============================================================================
 */
class Validate {

	// Stores error messages for checked fields
	private static $_errors = array();

	/**
	 * -------------------------------------------------------------------------
	 * Used to check if there were any errors during validation. Returns 
	 * boolean.
	 * -------------------------------------------------------------------------
	 */
	public static function passed()
	{
		if(self::$_errors)
			return false;
		return true;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Displays an error for a field in a block. This is used to embed error
	 * messages in your HTML. If no error is set for the field, nothing will
	 * be displayed.
	 *
	 * @param $field
	 *		The name of the field to get an error for.
	 * -------------------------------------------------------------------------
	 */
	public static function error($field)
	{
		if(isset(self::$_errors[$field]))
			View::load('Validate', 'validate_error',
				array('error' => self::$_errors[$field]));
	}

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
	public static function check($field_name, $display_name, $checks, $data = null)
	{	
		if(is_null($data))
			$data = $_POST;

		foreach($checks as $check)
		{
			Debug::log('Validate', 'Performing check "%s" on field "%s"',
				$check, $field_name);

			if(!call_user_func(array('self', '_' . $check), 
				$field_name, $display_name, $data))
			{
				Debug::log('Validate', 'Check "%s" failed for field "%s"', 
					$check, $field_name);

				return false;
			}
		}

		return true;
	}

	// TODO
	private static function _required($field, $display_name, $data)
	{
		$data[$field] = trim($data[$field]);
		if(!isset($data[$field]) || !strlen($data[$field]))
		{
			self::$_errors[$field] = sprintf(VALIDATE_REQUIRED, $display_name); 
			return false;
		}

		return true;
	}

	// TODO
	private static function _valid_email($field, $display_name, $data)
	{
		$status = isset($data[$field]);

		if($status && function_exists('filter_var')) 
		{
      		if(filter_var($data[$field], FILTER_VALIDATE_EMAIL) === false)
        		$status = false;
    	} 
		elseif($status) 
		{
			$status = preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $data[$field]);
    	}

		if(!$status)
			self::$_errors[$field] = sprintf(VALIDATE_EMAIL, $display_name);

		return $status;
	}

}
