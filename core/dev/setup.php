<?php return array(


    'configs' => array(
        'dev.debug_enabled' => false,
        'dev.debug_watch' => array(), // Only these modules will be output from debug
        'dev.debug_ignore' => array() // A list of modules to ignore, all others will be displayed
    ),


    'events' => array(
        'caffeine.finished' => function() {
            Dev::outputDebug();
        }
    )


);
