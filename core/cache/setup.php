<?php return array(

    'configs' => array(
        'cache.default_expire_time' => '24 hours' // Uses the strtotime function, any supporting syntax will work
    ),

    'events' => array(
        'cron.run' => function() {
            Cache::clearExpired();
        }
    )

);
