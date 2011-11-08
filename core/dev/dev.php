<?php

class Dev {
    
    private static $_debug = array();

    public static function debug($module, $message) {
        self::$_debug[] = array(time(), strtolower(trim($module)), $message);
    }

    public static function outputDebug()
    {
        if(Config::get('dev.debug_enabled'))
        {
            $html = '<table width="100%" border="1" cellpadding="5">';
            $html .= '<tr><th colspan="3">Debug Output</th></tr>';
            $html .= '<tr><th>Timestamp</th><th>Module</th><th>Message</th></tr>';
            
            foreach(self::$_debug as $d)
            {
                $html .= '<tr>';
                $html .= '<td>' . $d[0] . '</td>';
                $html .= '<td>' . $d[1] . '</td>';
                $html .= '<td>' . $d[2] . '</td>';
                $html .= '</tr>';
            }

            $html .= '</table>';

            echo $html;
        }
    }

}
