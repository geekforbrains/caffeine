<?php

class Multilanguage extends Module {

    private static $_registeredModules = null;
    private static $_moduleContent = null;

    /**
     * Triggers the multilanguage.register_modules event, if it hasn't been triggered already and returns
     * an array of module names that have been registered.
     */
    public static function getRegisteredModules()
    {
        if(is_null(self::$_registeredModules))
        {
            self::$_registeredModules = array();
            Event::trigger('multilanguage.register_modules', null, array('Multilanguage', 'registerModule'));
        }

        return self::$_registeredModules;
    }

    /**
     * Triggers the multilanguage.module_content[module] event and returns the array of registerd content returned.
     */
    public static function getModuleContent($module)
    {
        Event::trigger('multilanguage.module_content[' . $module . ']', null, array('Multilanguage', 'storeModuleContent'));
        return self::$_moduleContent;
    }

    /**
     * Callback for the multilanguage.register_modules event.
     */
    public static function registerModule($data) {
        self::$_registeredModules[] = ucfirst(strtolower(trim($data)));
    }

    /**
     * Callback for the multilanguage.module_content[module] event.
     */
    public static function storeModuleContent($data) {
        self::$_moduleContent = $data;
    }

}
