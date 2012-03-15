<?php return array(

    'configs' => array(
        'db.name' => null,
        'db.user' => null,
        'db.pass' => null,
        'db.host' => null,
        'db.driver' => 'mysql',
        'db.engine' => 'MyISAM',
        'db.enable_url_runner' => false
    ),

    'routes' => array(
        'db/:slug' => array(
            'callback' => array('db', 'runner')
        ),
        'db/:slug/:slug' => array( // Used for /db/install/force
            'callback' => array('db', 'runner')
        )
    )

);
