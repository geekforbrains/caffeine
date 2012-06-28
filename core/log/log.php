<?php

class Log extends Module {
    
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
     * TODO
     */
    private static function _log($type, $module, $message)
    {
        if(IS_CLI)
            fwrite(STDOUT, sprintf("[%s] $module: $message\n", strtoupper($type)));

        elseif(Config::get(sprintf('log.%s_enabled', $type)))
            self::_writeLog($type, $module, $message);
    }

    /**
     * TODO
     */
    private static function _writeLog($type, $module, $message)
    {
        $logFile = Config::get(sprintf('log.%s_file', $type));

        if(!$logFile)
            return;

        $log = sprintf("[%s] %s: %s\n", date('Y-m-d H:i:s'), $module, $message);

        if(!$handle = fopen($logFile, 'a'))
            trigger_error('Cant open log file for writing: ' . $logFile, E_USER_ERROR);

        if(fwrite($handle, $log) === false)
            trigger_error('Cant write to log file: ' . $logFile, E_USER_ERROR);

        fclose($handle);
    }

}
