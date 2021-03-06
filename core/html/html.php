<?php

class Html extends Module {

    /**
     * Calls a helper based on the method name. If the method is called with params, the
     * classes "get" method will be called automatically. Alternatively, you can chain methods
     * to call other methods within the class.
     *
     * Example of short-hand method:
     * Html::a('some/path', 'My Title'); Is the same as:  Html::a()->get('some/path', 'My Title');
     *
     * Example of method chaining:
     * Html::form()->open('some/path');
     *
     * @param string $name The name of the HTML helper class to call
     * @param mixed $args Optional params to be passed to the called method directly, used for short-hand calls
     * @return mixed
     */
    public static function __callStatic($name, $args)
    {
        $class = 'Html_' . ucfirst($name);
        $obj = new $class($args);

        if($args && method_exists($obj, 'get'))
            return call_user_func_array(array($obj, 'get'), $args);

        return $obj;
    }

}
