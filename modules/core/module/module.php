<?php

class Module {

    /**
     * When unkown static methods are called, we'll assume its the name of a model.
     */
    public static function __callStatic($name, $args)
    {
        $module = ucfirst(strtolower(get_called_class()));
        $model = sprintf('%s_%sModel', $module, ucfirst($name));
        return new $model();
    }

}
