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
         * The debug log file, relative to the current sites "files/" directory, that debug
         * messages will be written to. Messages will only be written to file if the
         * log.debug_to_file config is set to "true".
         */
        'log.debug_file' => sprintf('logs/debug_%s.php', date('Y_m_d')),

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
         * The error log file, relative to the current sites "files/" directory, that error
         * messages will be written to. 
         */
        'log.error_file' => sprintf('logs/error_%s.php', date('Y_m_d'))
    ),

    'events' => array(
        'caffeine.finished' => function() {
            Log::output();
        }
    )

);
