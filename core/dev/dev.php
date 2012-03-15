<?php

class Dev extends Module {
    
    /**
     * Stores debug information loaded throughout the application.
     */
    private static $_debug = array();

    /**
     * Stores a debug message for output later.
     *
     * @param string $module The module name setting the message (this is to determine where the message came from)
     * @param string $message The debug message to output
     */
    public static function debug($module, $message)
    {
        if(IS_CLI)
            fwrite(STDOUT, "$module - $message\n");
        else
            self::$_debug[] = array(time(), strtolower(trim($module)), $message);
    }

    /**
     * Outputs all debug messages to the browser. This method is called via the
     * "caffeine.finished" event. There's no need to call it directly.
     */
    public static function outputDebug()
    {
        if(Config::get('dev.debug_enabled'))
        {
            $tableAttr = array('width' => '100%', 'border' => 1, 'cellpadding' => 5);
            $headers = array('Timestamp', 'Module', 'Message');
            $rows = self::$_debug;

            if(!$rows)
            {
                $rows[] = array(
                    array(
                        '<em>No debug messages.</em>',
                        'attributes' => array(
                            'colspan' => 3
                        )
                    )
                );
            }

            echo Html::table()->build($headers, $rows, $tableAttr);
        }
    }

}
