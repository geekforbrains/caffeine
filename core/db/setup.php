<?php return array(

    'configs' => array(
        'db.name' => null,
        'db.user' => null,
        'db.pass' => null,
        'db.host' => null,
        'db.driver' => 'mysql',
        'db.engine' => 'MyISAM', // Used in ORM when creating tables

        // Creates model tables and keeps them up to date based on model fields.
        // Should be disabled on production server
        'db.install' => false 
    ),

    'routes' => array(
        'db/install' => array(
            'callback' => array('db', 'install')
        )
    )

);
