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
         * Custom modules located in modules/ or sites/<site>/modules/ must
         * be enabled by defining them in the array below.
         */
        'system.enabled_custom_modules' => array(),

        /**
         * All core modules are loaded by default. If for some reason you need to
         * disable one (ex: multilanguage), add it to this array.
         */
        'system.disabled_core_modules' => array(),

        /**
         * Sets the timezone for the entire application. Default is UTC.
         */
        'system.timezone' => 'UTC',

        /**
         * When true, Caffeine will return the errors/maintenance.php view and halt
         * further execution.
         */
        'system.maintenance_mode' => false
    )

);
