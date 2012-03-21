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

    /**
     * The following routes are used for calling commands in the db runner, such as
     * install, update and optimize. The ability to run these commands must bet set
     * in the setup.php config before they'll work.
     */
    'routes' => array(
        'db/:slug' => array(
            'callback' => array('db', 'runner')
        ),
        'db/:slug/:slug' => array( // Used for /db/install/force
            'callback' => array('db', 'runner')
        )
    )

);
