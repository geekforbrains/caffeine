<?php

class BizDir_Events {

    public static function path_callbacks()
    {
        return array(
            'directory' => array(
                'title' => 'Business Directory',
                'callback' => array('BizDir', 'view'),
                'auth' => true
            ),
            'directory/category/%s' => array(
                'title' => 'Business Category',
                'callback' => array('BizDir', 'category'),
                'auth' => true
            ),
            'directory/details/%s' => array(
                'title' => 'Business Details',
                'callback' => array('BizDir', 'details'),
                'auth' => true
            )
        );
    }

    public static function database_install()
    {
        return array(

        );
    }

}
