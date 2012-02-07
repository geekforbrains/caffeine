<?php return array(

    'permissions' => array(
        'multilanguage.admin' => 'Administer multilanguage',

        'multilanguage.admin_modules' => 'Administer modules',
        'multilanguage.manage_modules' => 'Manage modules',

        'multilanguage.admin_languages' => 'Administer languages',
        'multilanguage.manage_languages' => 'Manage languages'
    ),

    'routes' => array(
        'admin/multilanguage' => array(
            'title' => 'Multilanguage',
            'redirect' => 'admin/multilanguage/modules',
            'permissions' => array('multilanguage.admin')
        ),

        // Modules
        'admin/multilanguage/modules' => array(
            'title' => 'Modules',
            'redirect' => 'admin/multilanguage/modules/manage',
            'permissions' => array('multilanguage.admin_modules')
        ),
        'admin/multilanguage/modules/manage' => array(
            'title' => 'Manage',
            'callback' => array('admin_module', 'manage'),
            'permissions' => array('multilanguage.manage_modules')
        ),

        // Languages
        'admin/multilanguage/languages' => array(
            'title' => 'Languages',
            'redirect' => 'admin/multilanguage/languages/manage',
            'permissions' => array('multilanguage.admin_languages')
        ),
        'admin/multilanguage/languages/manage' => array(
            'title' => 'Manage',
            'callback' => array('admin_language', 'manage'),
            'permissions' => array('multilanguage.manage_languages')
        )
    )

); 
