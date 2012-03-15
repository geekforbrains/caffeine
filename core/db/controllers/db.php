<?php

class Db_DbController extends Controller {

    /**
     * TODO
     */
    public static function runner($cmd, $force = false)
    {
        $force = ($force == 'force') ? true : false;

        if(Config::get('db.enable_url_runner'))
        {
            switch($cmd)
            {
                case 'install':
                    Db_Runner::install($force);
                    break;

                case 'update':
                    Db_Runner::update();
                    break;

                case 'seed':
                    Db_Runner::seed();
                    break;

                default:
                    die('Invalid command, must be install, update or seed.');
            }

            exit();
        }
        else
            return ERROR_NOTFOUND;
    }

}
