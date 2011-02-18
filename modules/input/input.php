<?php
/**
 * =============================================================================
 * Input
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 *
 * Used for XSS cleaning and working with $_POST, $_GET, $_SESSION and $_COOKEIS
 * safely and effeciently.
 * =============================================================================
 */
class Input {

	/**
	 * -------------------------------------------------------------------------
	 * Used for getting a posted value. Returns $blank if the field is not set.
	 * Useful for setting value="" in HTML form fields when a field may or may
	 * no be posted.
	 * -------------------------------------------------------------------------
	 */
	public static function post($field, $blank = null)
	{
		if(isset($_POST[$field]))
			return $_POST[$field];
		return $blank;
	}

	// TODO More methods :P

}
