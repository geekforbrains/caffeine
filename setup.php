<?php return array(

    'configs' => array(
        /**
         * Database configurations. For a full list of possible configs, see
         * the setup.php file in core/db/setup.php
         */
        'db.name' => 'caffeine',
        'db.user' => 'username',
        'db.pass' => 'password',
        'db.host' => 'localhost',

        /**
         * Custom modules located in <root>/modules/ must be enabled by defining 
         * them in the array below. Modules within sites/<site>/modules are loaded 
         * automatically.
         */
        'system.enabled_custom_modules' => array('page', 'test'),

        /**
         * All core modules are loaded by default. If for some reason you need to
         * disable one (ex: multilanguage), add it to this array.
         */
        'system.disabled_core_modules' => array('multilanguage'),
        'multilanguage.enabled' => false,

        /**
         * Sets the timezone for the entire application. Default is UTC.
         *
         * A list of supported timezones can be found here:
         * http://ca.php.net/manual/en/timezones.php
         */
        'system.timezone' => 'UTC',

        /**
         * When true, Caffeine will display the errors/maintenance.php view and halt
         * further execution.
         */
        'system.maintenance_mode' => false,
    )

);
