<?php

class Dev extends Module {
    
    private static $_debug = array();

    public static function debug($module, $message)
    {
        /*
        if(Config::get('dev.debug_enabled'))
        {
            Config::set('dev.debug_enabled', false);
            Dev::message()->insert(array(
                'module' => $module,
                'message' => $message
            ));
            Config::set('dev.debug_enabled', true);
        }
        */

        self::$_debug[] = array(time(), strtolower(trim($module)), $message);
    }

    public static function outputDebug()
    {
        if(Config::get('dev.debug_enabled'))
        {
            $html = '<table width="100%" border="1" cellpadding="5">';
            $html .= '<tr><th colspan="3">Debug Output</th></tr>';
            $html .= '<tr><th>Timestamp</th><th>Module</th><th>Message</th></tr>';
            
            /*
            Config::set('dev.debug_enabled', false);
            $messages = Dev::message()->orderBy('created_at')->all();
            Config::set('dev.debug_enabled', true);
            */

            foreach(self::$_debug as $d)
            //foreach($messages as $m)
            {
                $html .= '<tr>';
                $html .= '<td>' . $d[0] . '</td>';
                $html .= '<td>' . $d[1] . '</td>';
                $html .= '<td>' . $d[2] . '</td>';
                /*
                $html .= '<td>' . $m->created_at . '</td>';
                $html .= '<td>' . $m->module . '</td>';
                $html .= '<td>' . $m->message . '</td>';
                */
                $html .= '</tr>';
            }

            $html .= '</table>';

            echo $html;

            /*
            Config::set('dev.debug_enabled', false);
            Dev::message()->truncate();
            Config::set('dev.debug_enabled', true);
            */
        }
    }

}
