<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
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

    // TODO Finish full country list
    public static $countries = array(
        'CA' => 'Canada',
        'US' => 'United States'
    );

	public static $provinces = array(
		'AB' => 'Alberta',
		'BC' => 'British Columbia',
		'MB' => 'Manitoba',
		'NB' => 'New Brunswick',
		'NL' => 'Newfoundland and Labrador',
		'NT' => 'Northwest Territories',
		'NS' => 'Nova Scotia',
		'NU' => 'Nunavut',
		'ON' => 'Ontario',
		'PE' => 'Prince Edward Island',
		'QC' => 'Quebec',
		'SK' => 'Saskatchewan',
		'YT' => 'Yukon'
	);

	public static $states = array(
		'AL'=>"Alabama",
		'AK'=>"Alaska",  
		'AZ'=>"Arizona",  
		'AR'=>"Arkansas",  
		'CA'=>"California",  
		'CO'=>"Colorado",  
		'CT'=>"Connecticut",  
		'DE'=>"Delaware",  
		'DC'=>"District Of Columbia",  
		'FL'=>"Florida",  
		'GA'=>"Georgia",  
		'HI'=>"Hawaii",  
		'ID'=>"Idaho",  
		'IL'=>"Illinois",  
		'IN'=>"Indiana",  
		'IA'=>"Iowa",  
		'KS'=>"Kansas",  
		'KY'=>"Kentucky",  
		'LA'=>"Louisiana",  
		'ME'=>"Maine",  
		'MD'=>"Maryland",  
		'MA'=>"Massachusetts",  
		'MI'=>"Michigan",  
		'MN'=>"Minnesota",  
		'MS'=>"Mississippi",  
		'MO'=>"Missouri",  
		'MT'=>"Montana",
		'NE'=>"Nebraska",
		'NV'=>"Nevada",
		'NH'=>"New Hampshire",
		'NJ'=>"New Jersey",
		'NM'=>"New Mexico",
		'NY'=>"New York",
		'NC'=>"North Carolina",
		'ND'=>"North Dakota",
		'OH'=>"Ohio",  
		'OK'=>"Oklahoma",  
		'OR'=>"Oregon",  
		'PA'=>"Pennsylvania",  
		'RI'=>"Rhode Island",  
		'SC'=>"South Carolina",  
		'SD'=>"South Dakota",
		'TN'=>"Tennessee",  
		'TX'=>"Texas",  
		'UT'=>"Utah",  
		'VT'=>"Vermont",  
		'VA'=>"Virginia",  
		'WA'=>"Washington",  
		'WV'=>"West Virginia",  
		'WI'=>"Wisconsin",  
		'WY'=>"Wyoming"
	);

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

	/**
	 * -------------------------------------------------------------------------
	 * Receives a string, shortens it, appends stuff to the end.
	 * -------------------------------------------------------------------------
	 */
	public static function truncate($string, $length, $append)
	{
		 $string = strip_tags($string);
		 $output = '';
	    settype($string, 'string');
	    settype($length, 'integer');
	    for($a = 0; $a < $length AND $a < strlen($string); $a++){
	        $output .= $string[$a];
	    }
	    $output .= $append;

	    return $output;
	}

}
