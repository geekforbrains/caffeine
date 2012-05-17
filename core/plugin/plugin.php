<?php

class Plugin extends Module {

    /**
     * TODO
     */
    public static function load($plugin, $class = null, $args = array())
    {
        if(strstr($plugin, '/'))
            $path = Config::get('plugin.dir') . $plugin;
        else
            $path = Config::get('plugin.dir') . $plugin . '/' . $plugin . EXT;

        if(file_exists($path))
        {
            require_once($path);

            if(is_null($class))
                $class = $plugin;

            if(class_exists($class))
            {
                if($args)
                {
                    $reflect  = new ReflectionClass($class);
                    return $reflect->newInstanceArgs($args);
                }
                else
                    return new $class();
            }
            else
                Log::debug('plugin', 'Could not find class "' . $class . '" within plugin: ' . $plugin);

            // If we got this far, plugin didnt contain a class but may just be a list of functions
            // We return true so the implementer knows its ready
            return true;
        }

        Log::error('plugin', 'Could not find plugin: ' . $plugin);
        return false;
    }

}
