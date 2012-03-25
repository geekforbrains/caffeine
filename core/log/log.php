<?php

class Log extends Module {
    
    /**
     * Stores messages written via Log::debug() or Log::error()
     */
    private static $_logs = array(
        'debug' => array(),
        'error' => array()
    );

    /**
     * Stores a debug message for output later or to be written to file.
     *
     * @param string $module The module name setting the message (this is to determine where the message came from)
     * @param string $message The debug message to output
     */
    public static function debug($module, $message) {
        self::_log('debug', $module, $message);
    }

    /**
     * Stores an error message for output later or to be written to file.
     *
     * @param string $module The module name setting the message (this is to determine where the message came from)
     * @param string $message The error message to output
     */
    public static function error($module, $message) {
        self::_log('error', $module, $message);
    }

    /**
     * Gets all the log types, checks if their configs are enabled but are not set to write to file
     * and outputs them to the browser in their own tables.
     */
    public static function output()
    {
        foreach(self::$_logs as $type => $logs)
        {
            if(Config::get(sprintf('log.%s_enabled', $type)) && !Config::get(sprintf('log.%s_to_file', $type)))
            {
                $table = Html::table(array('border' => '1', 'width' => '100%', 'class' => 'caffeine_' . $type));
                $header = $table->addHeader();
                $header->addCol(strtoupper($type), array('colspan' => 3));

                $titles = $table->addRow();
                $titles->addCol('<strong>Timestamp</strong>');
                $titles->addCol('<strong>Module</strong>');
                $titles->addCol('<strong>Message</strong>');

                if($logs)
                {
                    foreach($logs as $l)
                    {
                        $row = $table->addRow();
                        $row->addCol($l[0]); // Timestamp
                        $row->addCol($l[1]); // Module name
                        $row->addCol($l[2]); // Message
                    }
                }
                else
                    $table->addRow()->addCol('<em>No logs.</em>', array('colspan' => 3));

                echo $table->render();
            }
        }

    }

    /**
     * Determines if we're in CLI mode or not and either outputs the message to 
     * the browser or console if debug/error is enabled in the config. If write to file
     * is enabled for either debug/error the message will be written instead.
     */
    private static function _log($type, $module, $message)
    {
        // Always write to CLI
        if(IS_CLI)
            fwrite(STDOUT, sprintf("[%s] $module: $message\n", strtoupper($type)));

        
        if(Config::get(sprintf('log.%s_enabled', $type)))
        {
            if(!Config::get(sprintf('log.%s_to_file', $type)))
            {
                self::$_logs[$type][] = array(
                    date('Y-m-d H:i:s'), 
                    strtolower(trim($module)), 
                    $message
                );
            }
            else
                self::_writeLog($type, $module, $message);
        }
    }

    private static function _writeLog($type, $module, $message)
    {
        $logFile = ROOT . Media::getFilesPath() . Config::get(sprintf('log.%s_file', $type));
        $logDir = implode('/', explode('/', $logFile, -1));

        if(!file_exists($logDir))
        {
            $filesPath = ROOT . Media::getFilesPath();

            if(is_writable($filesPath))
                mkdir($logDir);
            else
                die('Unable to create log directory because your files directory isn\'t writable.');
        }

        if(!is_writable($logDir))
            die('Unable to write log file because your log directory isn\'t writable.');

        if(!file_exists($logFile))
        {
            $header = "<?php if(!defined('ROOT')) exit; ?>\n";

            if(!$handle = fopen($logFile, 'a'))
                die('Cant create log file: ' . $logFile);

            if(fwrite($handle, $header) === false)
                die('Cant write to log file: ' . $logFile);

            fclose($handle);
        }

        $log = sprintf("[%s] %s: %s\n", date('Y-m-d H:i:s'), $module, $message);

        if(!$handle = fopen($logFile, 'a'))
            die('Cant open log file for writing: ' . $logFile);

        if(fwrite($handle, $log) === false)
            die('Cant write to log file: ' . $logFile);

        fclose($handle);
    }

}
