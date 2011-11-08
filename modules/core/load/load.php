<?php

class Load extends Module {
    
    private static $_modules = array();
    private static $_modulePaths = array();
    private static $_models = array();

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
     * TODO Update to following functionality
     *
     * All modules and classes are loaded automatically. They are loaded in the following order.
     *
     * - <root>/sites/<current>/module...
     * - <root>/modules/custom/module...
     * - <root>/modules/core/module...
     *
     * In this way a site can override functinoality entirely, a custom module can create new functionality
     * or override the core, and the core modules provide the base needed by caffeine
     *
     * When overriding a module, you should copy all of the original module contents to the lower directory
     * and then add/remove the functionality you want
     */
    public static function auto($class)
    {
        echo "Autoloading: $class<br />";

        $class = strtolower($class);
        $dir = $class;
        $file = $class;
        
        // Class contains underscore, determine if its a sub class, controller or model
        if(strstr($class, '_'))
        {
            $bits = explode('_', $class);
            $dir = $bits[0];

            // Module_MyController => module/controllers/my.php
            if(strstr($bits[1], 'controller'))
                $file = sprintf('controllers/%s', str_replace('controller', '', $bits[1]));

            // Module_MyModel => module/models/my.php
            else if(strstr($bits[1], 'model'))
                $file = sprintf('models/%s', str_replace('model', '', $bits[1]));

            // Module_Subclass => module/subclass.php
            else
                $file = $bits[1];
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
     *
     * Order search is:
     * - <root>/sites/<current>/setup.php
     * - <root>/setup.php
     * - <root>/modules/<module>/setup.php
     *
     * The order of setting priority is top to bottom. So settings that exist in
     * the first file will override any of the same setting in files lower
     * down the chain.
     */
    public static function setupFiles()
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
                ROOT . 'modules/custom/',
                ROOT . 'modules/core/'
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
                        self::$_modules[] = $i;
                }
            }
        }

        return self::$_modules;
    }

    public static function getModels()
    {
        if(!self::$_models)
        {
            $paths = self::getModulePaths();

            foreach($paths as $path)
            {
                $modules = scandir($path);

                foreach($modules as $module)
                {
                    if($module{0} == '.')
                        continue;

                    $modelsPath = sprintf('%s%s/models/', $path, $module);

                    if(file_exists($modelsPath))
                    {
                        $models = scandir($modelsPath);

                        foreach($models as $model)
                        {
                            if($model{0} == '.')
                                continue;

                            $class = sprintf('%s_%sModel', ucfirst($module), ucfirst(str_replace(EXT, '', $model)));
                            self::$_models[] = $class;
                            /*
                            $model = new $class();
                            $model->createTable();
                            //call_user_func(array(ucfirst($module), $model))->createTable();
                            */
                        }
                    }
                }
            }
        }

        return self::$_models;
    }

    private static function _loadSetupFile($setupFile, $module = null)
    {
        echo "Loading Setup: $setupFile<br />";
        $setup = require($setupFile);

        foreach(self::$_setupModules as $option => $class)
        {
            if(isset($setup[$option]))
                call_user_func_array(array($class, 'load'), array($setup[$option], $module));
        }
    }

}
