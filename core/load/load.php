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
     * @param string $class The class name to find and attempt to load.
     */
    public static function auto($class)
    {
        $class = strtolower($class);
        $dir = $class;
        $file = $class;
        
        // Class contains underscore, determine if its a sub class, controller or model
        if(strstr($class, '_'))
        {
            $bits = explode('_', $class, 2); // Only explode first underscore, ignore rest
            $dir = $bits[0];

            // Module_MyController => module/controllers/my.php
            // Module_Admin_MyController => /module/controllers/admin_my.php
            if(String::endsWith($bits[1], 'controller'))
                $file = sprintf('controllers/%s', str_replace('controller', '', $bits[1]));

            // Module_MyModel => module/models/my.php
            elseif(String::endsWith($bits[1], 'model'))
                $file = sprintf('models/%s', str_replace('model', '', $bits[1]));

            // Module_Subclass => module/subclass.php
            /*
            else
                $file = $bits[1];
            */
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
     * Finds and loads all module setup files.
     */
    public static function loadSetupFiles()
    {
        if(file_exists($siteSetup = Site::getPath() . 'setup' . EXT))
            self::_loadSetupFile($siteSetup);

        if(file_exists($rootSetup = ROOT . 'setup' . EXT))
            self::_loadSetupFile($rootSetup);

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

                $items = scandir($path);
                foreach($items as $i)
                {
                    if($i{0} == '.')
                        continue;

                    if(!in_array($i, self::$_modules))
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

    private static function _loadSetupFile($setupFile, $module = null)
    {
        $setup = require($setupFile);

        foreach(self::$_setupModules as $option => $class)
        {
            if(isset($setup[$option]))
                call_user_func_array(array($class, 'load'), array($setup[$option], $module));
        }
    }

}
