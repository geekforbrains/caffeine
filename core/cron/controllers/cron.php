<?php

class Cron_CronController extends Controller {

    public static function run($passphrase)
    {
        if($passphrase == Config::get('cron.passphrase'))
            Event::trigger('cron.run');
        else
            Dev::debug('cron', 'Cant run cron job, invalid passphrase');

        Dev::outputDebug();

        exit();
    }

}
