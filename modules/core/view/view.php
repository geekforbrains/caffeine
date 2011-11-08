<?php

class View extends Module {

    /**
     * Stores loaded views to be rendered at end of execution
     */
    private static $_views = array();

    /**
     * Stores any data loaded that will be injected into views during rendering.
     */
    private static $_data = array();

    /**
     * Holds the path to the current error, if any. 
     */
    private static $_error = 0;

    /**
     * Returns the full path to the views directory being used. This will change based on the
     * current site directory.
     */
    public static function getPath() {
        return Site::getPath() . Config::get('view.dir');
    }

    /**
     * Sets the error view to be loaded based on the given code. This will stop any views
     * loaded from being rendered and will only output the error view.
     *
     * @param int $code The error code to display
     */
    public static function error($code) {
        self::$_error = self::getPath() . 'errors/' . $code . EXT;
    }

    /**
     * Stores data to be injected into views. The data is stored as key, value pairs.
     *
     * The "key" represents the name of the variable in the view. The value is given
     * to the keys variable.
     *
     * An array of key value pairs can be passed instead of doing one at a time.
     */
    public static function data($key, $value = null)
    {
        if(is_array($key))
            self::$_data = array_merge(self::$_data, $key);
        else
            self::$_data[$key] = $value;
    }

    /**
     * Automatically loads a view based on the currently called controller method.
     *
     * The views are searched for and loaded in the following order.
     *
     * 1. views/module/controller_method.php
     * 2. views/module/controller.php
     * 3. views/module.php
     * 4. views/index.php
     */
    public static function load($module, $controller, $method)
    {
        $checks = array(
            sprintf('%s/%s_%s' . EXT, $module, $controller, $method),
            sprintf('%s/%s' . EXT, $module, $controller),
            sprintf('%s' . EXT, $module),
            Config::get('view.index')
        );

        foreach($checks as $file)
        {
            $filePath = self::getPath() . $file;
            if(file_exists($filePath))
            {
                echo "Loading View: $filePath<br />";
                self::$_views[] = $filePath;
                return;
            }
        }
    }

    /**
     * Renders any loaded views to the browser.
     */
    public static function render()
    {
        foreach(self::$_data as $k => $v)
            $$k = $v;

        if(self::$_error === 0)
        {
            foreach(self::$_views as $filePath)
                require_once($filePath);
        }
        else
            require_once(self::$_error);
    }

}
