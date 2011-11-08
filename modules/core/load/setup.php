<?php return array(

    'configs' => array(

        /**
         * A list of modules to enable. If this is empty, all modules are enabled (unless
         * the load.disabled_modules is set), otherwise, only the specified modules are loaded.
         *
         * NOTE: Core modules are ALWAYS loaded.
         */
        'load.enabled_modules' => array(),

        /**
         * Disables the modules specified in the array. This will also override the load.enabled_modules config.
         *
         * NOTE: Core modules are ALWAYS loaded.
         */
        'load.disabled_modules' => array()

    )

);
