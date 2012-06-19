<?php

class Load extends Module {
    
    /**
     * Stores the name of all modules available to the application.
     */
    private static $_modules = array();

    /**
     * Stores the relative path from ROOT to all available modules.
     */
    private static $_modulePaths = array();

    /**
     * Defines which modules the keys from the setup.php file are loaded into.
     */
    private static $_setupModules = array(
        'configs' => 'Config',
        'permissions' => 'User',
        'routes' => 'Router',
        'events' => 'Event'
    );

    /**
     * Handles autoloading PHP classes. This is the default way for all classes to be
     * loaded in Caffeine.
     *
     * This method is called due to spl_autoload_register() being set to this method.
     *
     * Module Format:
     *      MyModule = mymodule/mymodule.php
     *
     * Module Sub-Class Format:
     *      MyModule_Foo = mymodule/mymodule_foo.php
     *      MyModule_FooBar = mymodule/mymodule_foo_bar.php
     *
     * Controller Format:
     *      MyModule_HelloController = mymodule/controllers/hello.php
     *      MyModule_HelloWorldController = mymodule/controllers/hello_world.php
     *      MyModule_Hello_World_Controller = mymodule/controllers/hello_world.php
     *
     * Model Format:
     *      Same as controller format, except replace "Controller" with "Model" in class names.
     *
     * @param string $class The class name to find and attempt to load.
     */
    public static function auto($class)
    {
        $dir = strtolower($class);
        $file = $dir;
        
        // Class contains underscore, determine if its a sub class, controller or model
        if(strstr($class, '_'))
        {
            $bits = explode('_', $class, 2); // Only explode first underscore, ignore rest
            $dir = strtolower($bits[0]);

            // Check for controller
            if(String::endsWith($bits[1], 'Controller'))
                $file = self::_formatController($bits[1]);
                //$file = sprintf('controllers/%s', str_replace('controller', '', $bits[1]));

            // Check for model
            elseif(String::endsWith($bits[1], 'Model'))
                $file = self::_formatModel($bits[1]);
                //$file = sprintf('models/%s', str_replace('model', '', $bits[1]));

            // Subclass, always include module name infront of subclass files
            else
                $file = $dir . '_' . self::_formatSubClass($bits[1]);
        }

        foreach(self::$_modulePaths as $path)
        {
            $filePath = sprintf('%s%s/%s%s', $path, $dir, $file, EXT);
            if(file_exists($filePath))
            {
                require_once($filePath);
                return;
            }
        }
    }

    /**
     * Returns the full path to $file in the $module "assets" directory.
     *
     * @param string $module The module we need an asset file from.
     * @param string $file The filename to get from the modules assets/ dir.
     * @param boolean $load If false, the file wont automatically be loaded, only the file path returned.
     *
     * @return String if file exists, false otherwise.
     */
    public static function asset($module, $file, $load = true)
    {
        if($modulePath = self::getModulePath($module))
        {
            $filePath = $modulePath . 'assets/' . $file; 

            if(file_exists($filePath))
            {
                if($load)
                    require_once($filePath);
                return $filePath;
            }
        }

        return false;
    }

    /**
     * Finds and loads all module setup files.
     */
    public static function loadSetupFiles()
    {
        if(file_exists($siteSetup = Site::getPath() . 'setup' . EXT))
            self::_loadSetupFile($siteSetup);

        if(file_exists($rootSetup = ROOT . 'setup' . EXT))
            self::_loadSetupFile($rootSetup);

        /*
        $paths = self::getModulePaths();
        foreach($paths as $path)
        {
            $modules = scandir($path);
            foreach($modules as $module)
            {
                if($module{0} == '.')   
                    continue;

                $setupFile = sprintf('%s%s/setup%s', $path, $module, EXT);

                if(file_exists($setupFile))
                    self::_loadSetupFile($setupFile, $module);
            }
        }
        */

        if($modules = self::getModules())
        {
            foreach($modules as $moduleName => $modulePath)
            {
                $setupFile = $modulePath . 'setup' . EXT;

                if(file_exists($setupFile))
                    self::_loadSetupFile($setupFile, $moduleName);
            }
        }
    }

    /**
     * Returns an array of paths to be searched for modules. This also determines where
     * modules are loaded from.
     */
    public static function getModulePaths()
    {
        if(!self::$_modulePaths)
        {
            self::$_modulePaths = array(
                ROOT . 'modules/',
                ROOT . 'core/'
            );

            $sitePath = Site::getPath() . 'modules/';

            if(file_exists($sitePath))
                array_unshift(self::$_modulePaths, $sitePath);
        }

        return self::$_modulePaths;
    }

    /**
     * Gets the name of all modules available. Module names are based on their directory name.
     *
     * This method takes into account the system.enabled_custom_modules and system.disabled_core_modules
     * configs. See the main setup.php file for more info.
     */
    public static function getModules()
    {
        if(!self::$_modules)
        {
            $paths = self::getModulePaths();

            foreach($paths as $path)
            {
                if(!file_exists($path))
                    continue;

                $inModules = strstr($path, ROOT . 'modules/');
                $inCore = strstr($path, ROOT . 'core/');

                $items = scandir($path);

                foreach($items as $i)
                {
                    if($i{0} == '.')
                        continue;

                    if($inModules && !in_array($i, Config::get('system.enabled_custom_modules')))
                        continue;

                    elseif($inCore && in_array($i, Config::get('system.disabled_core_modules')))
                        continue;

                    if(!isset(self::$_modules[$i]))
                        self::$_modules[$i] = $path . $i . '/';
                }
            }
        }

        return self::$_modules;
    }

    /**
     * Returns the full path to the given module. If the module doesn't exist, boolean false
     * is returned.
     */
    public static function getModulePath($module)
    {       
        $modules = self::getModules();
        if(isset($modules[$module]))
            return $modules[$module];
        return false;
    }

    /**
     * TODO Description
     */
    private static function _loadSetupFile($setupFile, $module = null)
    {
        $setup = require($setupFile);

        foreach(self::$_setupModules as $option => $class)
        {
            if(isset($setup[$option]))
                call_user_func_array(array($class, 'load'), array($setup[$option], $module));
        }
    }

    /**
     * TODO Description
     */
    private static function _formatFilename($string) {
        return strtolower(String::splitCamelCase($string));
    }

    /**
     * TODO Description
     */
    private static function _formatController($controller) {
        return 'controllers/' . self::_formatFilename(substr($controller, 0, -10));
    }

    /**
     * TODO Description
     */
    private static function _formatModel($model) {
        return 'models/' . self::_formatFilename(substr($model, 0, -5));
    }

    /**
     * TODO Description
     */
    private static function _formatSubClass($subclass) {
        return self::_formatFilename($subclass);
    }

}
