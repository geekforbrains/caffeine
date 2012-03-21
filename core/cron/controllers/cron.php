<?php

class Cron_CronController extends Controller {

    /**
     * Runs a cron job. This method is called by visting the url described in setup.php
     * It is typically called via a unix cron job (hence the name)
     *
     * All modules wanting to make use of cron jobs should implement the "cron.run"
     * event in their setup.php files.
     *
     * @param string $passphrase The cron passphrase defined in setup.php - to avoid public miss-use
     */
    public static function run($passphrase)
    {
        if($passphrase == Config::get('cron.passphrase'))
            Event::trigger('cron.run');
        else
            Log::debug('cron', 'Cant run cron job, invalid passphrase');

        Log::output();
        exit();
    }

}
