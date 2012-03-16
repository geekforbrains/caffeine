<?php

class Db_DbController extends Controller {

    /**
     * Allows database install, update, seed and optimize commands to be run
     * through the URL instead of forcing people to use the CLI.
     *
     * The config "db.enabled_url_runner" must be set to "true" to allow running
     * commands otherwise 404 will be returned.
     *
     * The "db.enable_url_runner" command should be set to "false" in a production
     * environment.
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

                case 'optimize':
                    Db_Runner::optimize();
                    break;

                default:
                    die('Invalid command. Must be install, update ,seed or optimize.');
            }

            exit();
        }
        else
            return ERROR_NOTFOUND;
    }

}
