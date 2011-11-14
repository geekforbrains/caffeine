<?php return array(

    'configs' => array(
        'cron.passphrase' => 'mypassphrase' // ALWAYS make a custom passphrase or anybody can run your cron jobs!!!
    ),

    'routes' => array(
        'cron/run/%' => array(
            'callback' => array('cron', 'run'),
            'hidden' => true
        )
    )

);
