<?php

/**
 * When loading views, the system should follow this functionality.
 *
 * 1. Check for main view files (within the views directory)
 * 2. Check for main blocks that can be injected into the view just loaded
 */
class View extends Module {

    /**
     * Stores the current full path view are to be loaded from.
     */
    private static $_path = null;

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
     * Determines if the View::load method is being overriden by another module via the view.load event
     */
    private static $_loadOverride = false;

    /**
     * Stores the current view title. This is used to output the titles to views as well as the <title>
     * for seo.
     */
    private static $_title = null;

    /**
     * Callback for the view.load event. Sets the returned value as the view to load and sets
     * the _loadOverride property to true.
     */
    public static function setLoadOverride($response) 
    {
        if($response)
        {
            self::$_loadOverride = true;
            self::$_views[] = $response;
        }
    }

    /**
     * Sets the current page title.
     */
    public static function setTitle($title) {
        self::$_title = $title;
    }

    /**
     * Gets the current page title.
     */
    public static function getTitle() {
        return self::$_title;
    }

    /**
     * Changes the path views are loaded from. This is mainly used by the "admin" module to
     * change the views directory when accessing admin pages.
     *
     * @param string $path The full path, minus the view directory name, to be loaded
     */
    public static function setPath($path)
    {
        self::$_path = $path . Config::get('view.dir');
        Dev::debug('view', 'Setting view path to: ' . self::$_path);
    }

    /**
     * Returns the full path to the views directory being used. This will change based on the
     * current site directory or if another module modifies the path via View::setPath.
     *
     * @return string Full path to current views directory
     */
    public static function getPath()
    {
        if(is_null(self::$_path))
            self::$_path = Site::getPath() . Config::get('view.dir');
        return self::$_path;
    }

    /**
     * Gets the relative URL to the current view base href. This is used for setting the <base href="" />
     * tag within view files.
     */
    public static function getBaseHref()
    {
        $bits = explode(ROOT, self::getPath());
        return Url::to($bits[1]);
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
     *
     * @param string $key The name of the variable to create in the view
     * @param mixed $value The value associated with the variable $key
     */
    public static function data($key, $value = null)
    {
        if(is_array($key))
            self::$_data = array_merge(self::$_data, $key);
        else
            self::$_data[$key] = $value;
    }

    /**
     * Includes a view file relative to the current url path. Does not need to start or end
     * with slashes and should not include the .php file extension.
     *
     * Example: View::insert('includes/header'); ?>
     *
     * @param string $view The relative path to the view file to insert
     */
    public static function insert($view)
    {
        $viewFile = self::getPath() . $view . EXT;
        Dev::debug('view', 'Inserting view: ' . $viewFile);
        require($viewFile);
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
     *
     * @param string $module The module name
     * @param string $controller The module controller name
     * @param string $method The method within the controller
     */
    public static function load($module, $controller, $method)
    {
        $module = strtolower($module);

        // Clean controller name
        $conBits = explode('_', strtolower($controller), 2); // Only explode module off, leave other underscores
        $controller = str_replace('controller', '', $conBits[1]);

        // Give other modules a chance to override view loading functionality
        Event::trigger('view.load', array($module, $controller, $method), array('View', 'setLoadOverride'));

        // If the load module was overriden, reset back to false (incase something else is loaded) and return
        if(self::$_loadOverride)
        {
            self::$_loadOverride = false;
            return;
        }

        $checks = array(
            sprintf('%s/%s_%s' . EXT, $module, $controller, $method),
            sprintf('%s/%s' . EXT, $module, $controller),
            sprintf('%s' . EXT, $module),
            Config::get('view.index')
        );

        foreach($checks as $file)
        {
            $filePath = self::getPath() . $file;
            Dev::debug('view', 'Checking for view: ' . $filePath);

            if(file_exists($filePath))
            {
                Dev::debug('view', 'Loading view: ' . $filePath);
                self::$_views[] = $filePath;
                return;
            }
        }
    }

    /**
     * Like View::insert but view is added to the views property and rendered during output.
     * This allows views loaded this way to be cached.
     */
    public static function directLoad($view)
    {
        $filePath = self::getPath() . $view . EXT;

        if(file_exists($filePath))
        {
            self::$_views[] = $filePath;
            return true;
        }

        return false;
    }

    /**
     * Used for loading a file from its path, providing variable data and 
	 * returning the generated HTML.
     *
     * @param $path string
     *      The full file path to be loaded.
     *
     * @param $data array
     *      An optional array of view data to be made available to the loaded.
     *
     * @return
     *      Returns a string of generated HTML.
     */
    public static function render($view_path, $view_data = array())
    {
        if($view_data)
            foreach($view_data as $k => $v)
                $$k = $v;
        
        ob_start();
        eval('?>' .file_get_contents($view_path). '<?');
        $buffer = ob_get_contents();
        ob_end_clean();
        
        return $buffer;
    }

    /**
     * Renders any views and outputs them to the browser.
     */
    public static function output()
    {
        Dev::debug('view', 'Outputting views to browser');

        if(self::$_error === 0)
        {
            $html = '';

            foreach(self::$_views as $viewFile)
                $html .= self::render($viewFile, self::$_data);

            // TODO Add caching (Cache::store())
            echo $html;
        }
        else
            require_once(self::$_error);
    }

}