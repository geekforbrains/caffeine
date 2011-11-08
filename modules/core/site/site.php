<?php

class Site extends Module {

    /**
     * Returns the full path of the current site directory being used.
     *
     * @return string Full path to current site directory.
     */
    public static function getPath()
    {
        // TODO Actually determine the site dir
        return ROOT . 'sites/default/';
    }

}
