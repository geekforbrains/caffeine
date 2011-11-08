<?php

class Html extends Module {

    /**
     * Calls a helper based on the method name.
     *
     * Example: Html::a('url', 'text'); => Html_A::build('url', 'text');
     */
    public static function __callStatic($name, $args)
    {
        $class = 'Html_' . ucfirst($name);
        return new $class();
    }

}
