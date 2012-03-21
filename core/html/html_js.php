<?php

class Html_Js {

    private static $_paths = array();

    /**
     * Stores the path to be js file to be loaded in a View.
     *
     * Example:
     *      Html::js()->add('relative/path/to/file.js', 'location')
     *
     * @param string $filePath The relative path to the js file.
     * @param string $area An optional area that js will be associated with, used with the get() method.
     */
    public function add($filePath, $area = 'default') {
    {
        if(!isset(self::$_paths[$area]))
            self::$_paths[$area] = array();

        self::$_paths[$area][] = $filePath;
    }

    /**
     * Gets all the js files for an area. 
     *
     * @param string $area An optional param for specifying the area to get js files for.
     */
    public function get($area = 'default')
    {
        if(isset(sef::$_paths[$area]))
            return self::$_paths[$area];

        return null;
    }

}
