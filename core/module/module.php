<?php

class Module {

    /**
     * When unkown static methods are called, we'll assume its the name of a model.
     */
    public static function __callStatic($name, $args) {
        return self::m($name);
    }

    /**
     * Method for calling a model directly, this is incase another method in the main module
     * file is the same name as a potential model name.
     *
     * Ex: Module::m('model')->find(id) instead of Module::model()->find(id)
     */
    public static function m($name)
    {
        $module = ucfirst(strtolower(get_called_class()));
        $model = sprintf('%s_%sModel', $module, ucfirst(strtolower($name)));

        if(class_exists($model))
            return new $model();
        return null;
    }

}
