<?php

class Db_DbController extends Controller {

    /**
     * Used for running database updates and installs through the browser by accessing
     * the route "db/install". Installing must be enabled in the config in order for this
     * to run, otherwise 404 is returned.
     */
    public static function install()
    {
        if(Config::get('db.install'))
        {
            Db::install();
            echo '<h1>Installing/Updating Database</h1>';
            Dev::outputDebug();
            exit();
        }
        else
            return ERROR_NOTFOUND;
    }

}
