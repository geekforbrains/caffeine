<?php

class Api_ApiController extends Controller {

    /**
     * Captures all segments passed after the /api/ route, breaks it into piecies and
     * determines which module to call, if any.
     *
     * Checks end of segment for either .xml or .json
     *
     * Minumum of 2 segments are required, Module and Method, all other segments are sent as params
     */
    public static function capture($segments)
    {
        if(substr($segments, -4) == '.xml')
        {
            $format = 'xml';
            $segments = substr($segments, 0, -4);
        }
        elseif(substr($segments, -5) == '.json')
        {
            $format = 'json';
            $segments = substr($segments, 0, -5);
        }
        else
        {
            die('Invalid format specified.');
        }
        
        $bits = explode('/', $segments);

        if(count($bits) < 2)
            die('not enough params');

        // module
        $module = sprintf('%s_api', $bits[0]);
        if(!class_exists($bits[0]))
        {
            die('no module by the name: ' . $bits[0]);
        }

        // method
        if(!method_exists($module, $bits[1]))
        {
            die('invalid method');
        }

        $params = (count($bits) > 2) ? array_slice($bits, 2) : array();
        $response = call_user_func_array(array($module, $bits[1]), $params);

        // TODO Format response based on format
        die($response);
    }

}
