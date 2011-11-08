<?php

class Config extends Module {

    public static $_configs = array();

    public static function getConfigs() {
        return self::$_configs;
    }

    public static function load($configs) {
        self::$_configs = array_merge($configs, self::$_configs);
    }

    public static function get($config) {
        return self::$_configs[$config];
    }

    public static function set($config, $value) {
        self::$_configs[$config] = $value;
    }

}
