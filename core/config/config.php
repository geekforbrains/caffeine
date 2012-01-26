<?php

class Config extends Module {

    /**
     * Holds configs loaded from all module setup.php files.
     */
    public static $_configs = array();

    /**
     * Get all configs loaded.
     *
     * @return array Array of configs loaded.
     */
    public static function getConfigs() {
        return self::$_configs;
    }

    /**
     * Loads an array of configs into the current configs. This is typically 
     * called from the Load module, and doesn't need to be called directly.
     *
     * @param array $configs An array of key value configs.
     */
    public static function load($configs) {
        self::$_configs = array_merge($configs, self::$_configs);
    }

    /**
     * Gets a single config value.
     *
     * @param string $config The key for the config to get
     * @return mixed Returns the config value if it exists, otherwise boolean false
     */
    public static function get($config) 
    {
        if(isset(self::$_configs[$config]))
            return self::$_configs[$config];
        return false;
    }

    /**
     * Sets a configs value. This typically should not be used, but its here just in case.
     * A better way to change a config value is to modify the setup.php file.
     *
     * @param string $config The config key to use
     * @param mixed $value The value to associate with $key
     */
    public static function set($config, $value) {
        self::$_configs[$config] = $value;
    }

}
