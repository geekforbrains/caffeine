<?php

class Html_Js {

    private static $_paths = array();

    // Html::js()->add('some/path', 'footer');
    public function add($filePath, $area = 'default') {
    {
        if(!isset(self::$_paths[$area]))
            self::$_paths[$area] = array();

        self::$_paths[$area][] = $filePath;
    }

    // Html::js()->get('footer');
    public function get($area = 'default')
    {
        if(isset(sef::$_paths[$area]))
            return self::$_paths[$area];

        return null;
    }

}
