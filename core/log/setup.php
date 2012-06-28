<?php return array(

    'configs' => array(
        /**
         * When enabled (true) all messages logged with the Log::debug() method will
         * either be displayed in the browser or written to file (see log.debug_to_file config).
         */
        'log.debug_enabled' => true,

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
        'log.debug_file' => '/tmp/caffeine_debug.log',

        /**
         * When enabled, all messages logged with the Log::error() method will either be
         * displayed in the browser or written to file (see log.error_to_file config).
         */
        'log.error_enabled' => true,

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
        'log.error_file' => '/tmp/caffeine_error.log',
    ),

);
