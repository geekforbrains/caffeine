<?php return array(

    'configs' => array(
        /**
         * When enabled, will perform checks on the url to determine if a new language is to be used
         * but also inserts new strings being translated via the t() function.
         */
        'multilanguage.enabled' => false
    ),

    'permissions' => array(
        'multilanguage.admin' => 'Administer multilanguage',

        'multilanguage.admin_modules' => 'Administer modules',
        'multilanguage.manage_modules' => 'Manage modules',

        'multilangauge.admin_strings' => 'Administer strings',
        'multilanguage.manage_strings' => 'Manage strings',

        'multilanguage.admin_languages' => 'Administer languages',
        'multilanguage.manage_languages' => 'Manage languages',
        'multilanguage.create_languages' => 'Create languages',
        'multilanguage.edit_languages' => 'Edit languages',
        'multilanguage.delete_languages' => 'Delete langauges'
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
        'admin/multilanguage/modules/manage/:slug' => array(
            'title' => 'Manage Module',
            'callback' => array('admin_module', 'manageModule'),
            'permissions' => array('multilanguage.manage_modules')
        ),
        'admin/multilanguage/modules/manage/:slug/:slug/:num' => array(
            'title' => 'Manage Content',
            'callback' => array('admin_module', 'manageContent'),
            'permissions' => array('multilanguage.manage_modules')
        ),

        // Strings
        'admin/multilanguage/strings' => array(
            'title' => 'Strings',
            'redirect' => 'admin/multilanguage/strings/manage',
            'permissions' => array('multilanguage.admin_strings')
        ),
        'admin/multilanguage/strings/manage' => array(
            'title' => 'Manage',
            'callback' => array('admin_string', 'manage'),
            'permissions' => array('multilanguage.manage_strings')
        ),
        'admin/multilanguage/strings/manage/:id' => array(
            'title' => 'Manage Content',
            'callback' => array('admin_string', 'manageContent'),
            'permissions' => array('multilanguage.manage_strings')
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
        ),
        'admin/multilanguage/languages/create' => array(
            'title' => 'Create',
            'callback' => array('admin_language', 'create'),
            'permissions' => array('multilanguage.create_languages')
        ),
        'admin/multilanguage/languages/edit/:num' => array(
            'title' => 'Edit Language',
            'callback' => array('admin_language', 'edit'),
            'permissions' => array('multilanguage.edit_languages')
        ),
        'admin/multilanguage/languages/delete/:num' => array(
            'callback' => array('admin_language', 'delete'),
            'permissions' => array('multilanguage.delete_languages')
        )
    )

); 
