<?php return array(

    'configs' => array(
        /**
         * When enabled (true) all messages logged with the Log::debug() method will
         * either be displayed in the browser or written to file (see log.debug_to_file config).
         */
        'log.debug_enabled' => false,

        /**
         * When enabled, messages logged with the Log::debug() method will be saved to a
         * file instead of being output to the browser. Note that log.debug_enabled must
         * be set to "true" as well.
         */
        'log.debug_to_file' => false,

        /**
         * The full file path to write debug messages to. This is "null" by default and will
         * not attempt to write to file unless a path is set.
         *
         * If you would like to log files relative to your applications path, use the "ROOT"
         * constant.
         *
         * Example: 'log.debug_file' => '/tmp/caffeine_debug.log'
         *
         * Note that you must ensure the path given exists and is writable.
         */
        'log.debug_file' => null,

        /**
         * When enabled, all messages logged with the Log::error() method will either be
         * displayed in the browser or written to file (see log.error_to_file config).
         */
        'log.error_enabled' => false,

        /**
         * When enabled, messages logged with the Log::error() method will be written to file
         * instead of being output to the browser. Note that log.error_enabled must be set to
         * "true" as well.
         */
        'log.error_to_file' => false,

        /**
         * The full file path to write error messages to. This is "null" by default and will
         * not attempt to write to file unless a path is set.
         *
         * If you would like to log files relative to your applications path, use the "ROOT"
         * constant.
         *
         * Example: 'log.error_file' => '/tmp/caffeine_error.log'
         *
         * Note that you must ensure the path given exists and is writable.
         */
        'log.error_file' => null,
    ),

    'events' => array(
        'caffeine.finished' => function() {
            Log::output();
        }
    )

);
