<?php

class String extends Module {

    private static $_plural = array(
        '/(quiz)$/i'               => "$1zes",
        '/^(ox)$/i'                => "$1en",
        '/([m|l])ouse$/i'          => "$1ice",
        '/(matr|vert|ind)ix|ex$/i' => "$1ices",
        '/(x|ch|ss|sh)$/i'         => "$1es",
        '/([^aeiouy]|qu)y$/i'      => "$1ies",
        '/(hive)$/i'               => "$1s",
        '/(?:([^f])fe|([lr])f)$/i' => "$1$2ves",
        '/(shea|lea|loa|thie)f$/i' => "$1ves",
        '/sis$/i'                  => "ses",
        '/([ti])um$/i'             => "$1a",
        '/(tomat|potat|ech|her|vet)o$/i'=> "$1oes",
        '/(bu)s$/i'                => "$1ses",
        '/(alias)$/i'              => "$1es",
        '/(octop)us$/i'            => "$1i",
        '/(ax|test)is$/i'          => "$1es",
        '/(us)$/i'                 => "$1es",
        '/s$/i'                    => "s",
        '/$/'                      => "s"
    );

    private static $_singular = array(
        '/(quiz)zes$/i'             => "$1",
        '/(matr)ices$/i'            => "$1ix",
        '/(vert|ind)ices$/i'        => "$1ex",
        '/^(ox)en$/i'               => "$1",
        '/(alias)es$/i'             => "$1",
        '/(octop|vir)i$/i'          => "$1us",
        '/(cris|ax|test)es$/i'      => "$1is",
        '/(shoe)s$/i'               => "$1",
        '/(o)es$/i'                 => "$1",
        '/(bus)es$/i'               => "$1",
        '/([m|l])ice$/i'            => "$1ouse",
        '/(x|ch|ss|sh)es$/i'        => "$1",
        '/(m)ovies$/i'              => "$1ovie",
        '/(s)eries$/i'              => "$1eries",
        '/([^aeiouy]|qu)ies$/i'     => "$1y",
        '/([lr])ves$/i'             => "$1f",
        '/(tive)s$/i'               => "$1",
        '/(hive)s$/i'               => "$1",
        '/(li|wi|kni)ves$/i'        => "$1fe",
        '/(shea|loa|lea|thie)ves$/i'=> "$1f",
        '/(^analy)ses$/i'           => "$1sis",
        '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i'  => "$1$2sis",
        '/([ti])a$/i'               => "$1um",
        '/(n)ews$/i'                => "$1ews",
        '/(h|bl)ouses$/i'           => "$1ouse",
        '/(corpse)s$/i'             => "$1",
        '/(us)es$/i'                => "$1",
        '/s$/i'                     => ""
    );

    private static $_irregular = array(
        'move'   => 'moves',
        'foot'   => 'feet',
        'goose'  => 'geese',
        'sex'    => 'sexes',
        'child'  => 'children',
        'man'    => 'men',
        'tooth'  => 'teeth',
        'person' => 'people'
    );

    private static $_uncountable = array(
        'sheep',
        'fish',
        'deer',
        'series',
        'species',
        'money',
        'rice',
        'information',
        'equipment'
    );

    /**
     * Converts a singular word to plural.
     *
     * @param string $string The singular word to convert to plural.
     *
     * @return string
     */
    public static function plural($string)
    {
        // save some time in the case that singular and plural are the same
        if(in_array(strtolower($string), self::$_uncountable))
            return $string;

        // check for irregular singular forms
        foreach(self::$_irregular as $pattern => $result)
        {
            $pattern = '/' . $pattern . '$/i';

            if(preg_match($pattern, $string))
                return preg_replace($pattern, $result, $string);
        }

        // check for matches using regular expressions
        foreach(self::$_plural as $pattern => $result)
        {
            if(preg_match($pattern, $string))
                return preg_replace($pattern, $result, $string);
        }

        return $string;
    }

    /**
     * Converts a word from plural to singular.
     *
     * @param string $string The plural word to convert to singular.
     *
     * @return string 
     */
    public static function singular($string)
    {
        // save some time in the case that singular and plural are the same
        if(in_array(strtolower($string), self::$_uncountable))
            return $string;

        // check for irregular plural forms
        foreach(self::$_irregular as $result => $pattern)
        {
            $pattern = '/' . $pattern . '$/i';

            if(preg_match($pattern, $string))
                return preg_replace($pattern, $result, $string);
        }

        // check for matches using regular expressions
        foreach(self::$_singular as $pattern => $result)
        {
            if(preg_match($pattern, $string))
                return preg_replace($pattern, $result, $string);
        }

        return $string;
    }

    /**
     * Determines if a given string starts with another string.
     *
     * @param string $string The string to check the beginning of.
     * @param string $start The string to check for at the beginning of $string.
     *
     * @return boolean
     */
    public static function startsWith($string, $start)
    {
        if(substr($string, 0, strlen($start)) == $start)
            return true;
        return false;
    }

    /**
     * Determines if a string ends with another string.
     *
     * @param string $string The string to check the end of.
     * @param string $end The string to check for at the end of $string.
     *
     * @return boolean
     */
    public static function endsWith($string, $end)
    {
        if(substr($string, -strlen($end)) == $end)
            return true;
        return false;
    }

    /**
     * Takes a string and returns it in "tag" format. A tag is all lower case
     * with only numbers and letters. Words are seperated by a "splitter".
     *
     * Example:
     *      "It's a tag!" = "its-a-tag"
     *      "My, myself & I" = "me-myself-and-i"
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
     */
    public static function tagify($string, $splitter = '-')
    {
        $string = trim(strtolower($string));
        $string = str_replace('&', 'and', $string);
        $string = preg_replace('/[^A-Za-z0-9\s]+/', '', $string); // Clear out anything that isn't a letter, number or space
        $string = preg_replace('/[\s]+/', '-', $string);

        return $string;
    }

    // Alias of tagify
    public static function slugify($string, $splitter = '-') {
        return self::tagify($string, $splitter);
    }

	/**
	 * Takes a string with the tokens %, %s, %d and converts them to regex
	 * patterns for anything, only words and only number respectively. 
	 */
	public static function regify($string)
	{
        /*
		return str_replace('%', '(.*?)',
			str_replace('%d', '([0-9]+)', 
			str_replace('%s', '([A-Za-z\-]+)', $string)));
        */

        $replacements = array(
            '([A-Za-z0-9\-]+)' => array(':slug'),
            '([A-Za-z\-]+)' => array('%s', ':alpha'),
            '([0-9]+)' => array('%d', ':num'),
            '(.*?)' => array('%', ':any')
        );

        foreach($replacements as $r => $keys)
        {
            foreach($keys as $k)
                $string = str_replace($k, $r, $string);
        }

        return $string;
	}

    /**
     * Shortens a string based on the given length to the nearest word. This means
     * it wont cut off part of a word, it'll reduce the string until it finds the end of
     * a whole word. If the string is shorter than the length, the $append will not be added.
     *
     * @param string $string The string you want to shorten
     * @param int $length The length to shorten the string to, to the nearest word
     * @param string $append A string value to append to shortened strings (ex: "...");
     * @param boolean $striptags Enables or disables the stripping of HTML tags from the string. Defaults to true.
     *
     * @return string
     */
    public static function truncate($string, $length, $append = null, $striptags = true)
    {
        if($striptags)
            $string = strip_tags($string);

        if(strlen($string) > $length)
        { 
            $shortened = '';
            for($i = $length; $i > 0; $i--)
            {
                if($string{$i} == ' ')
                {
                    $shortened = substr($string, 0, $i);
                    return substr($string, 0, $i) . $append;
                }
            }
        }

        return $string;
    }

}
