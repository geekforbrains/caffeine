<?php
/**
 * =============================================================================
 * String
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * 
 * A library for various ways of working with and manipulating strings.
 * =============================================================================
 */
class String {

    /**
     * -------------------------------------------------------------------------
     * Takes a string and returns it in "tag" format. A tag is all lower case
     * with only numbers and letters. Words are seperated by a "splitter".
     *
     * Example:
     *      "It's a tag!" = "its-a-tag"
     *
     * @param $string
     *      The string to convert to a tag.
     *
     * @param $splitter
     *      An optional argument to set the splitter (replacement for spaces).
     *      Defaults to "-".
     *
     * @return
     *      Returns the value of $string as a tag.
     * -------------------------------------------------------------------------
     */
    public static function tagify($string, $splitter = '-')
    {
        return trim(str_replace(' ', $splitter, preg_replace('#[^a-z0-9\s]+#', '', 
            preg_replace('#[-\s]+#', ' ', strtolower($string)))), $splitter);
    }

	/**
	 * -------------------------------------------------------------------------
	 * Takes a string with the tokens %, %s, %d and converts them to regex
	 * patterns for anything, only words and only number respectively. 
	 * -------------------------------------------------------------------------
	 */
	public static function regify($string)
	{
		return str_replace('%', '(.*?)',
			str_replace('%d', '([0-9]+)', 
			str_replace('%s', '([A-Za-z\-]+)', $string)));
	}

}
