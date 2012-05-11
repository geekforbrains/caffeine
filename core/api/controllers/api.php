<?php

class Api_ApiController extends Controller {

    /**
     * Captures all segments passed after the /api/ route, breaks it into piecies and
     * determines which module to call, if any.
     *
     * All data is json encoded. Additional formats (ie: XML) may be added later.
     *
     * Minumum of 2 segments are required, Module and Method, all other segments are sent as params
     */
    public static function capture($segments)
    {
        $bits = explode('/', $segments);

        if(count($bits) < 2)
        {
            self::_json(array(
                'code' => 400,
                'message' => 'API requires a minimum of 2 arguments (Module and Method).'
            ));
        }

        $method = null;
        $data = array();

        switch($_SERVER['REQUEST_METHOD'])
        {
            case 'GET':
                $method = sprintf('get%s', ucfirst($bits[1]));
                $data = $_GET;
                break;

            case 'POST':
                $method = sprintf('create%s', ucfirst($bits[1]));
                $data = $_POST;
                break;

            case 'PUT':
                $method = sprintf('update%s', ucfirst($bits[1]));
                parse_str(file_get_contents('php://input'), $data);
                break;

            case 'DELETE':
                $method = sprintf('delete%s', ucfirst($bits[1]));
                break;

            default:
                self::_json(array(
                    'code' => 400,
                    'message' => 'Invalid request method.'
                ));
        }

        $module = sprintf('%s_api', $bits[0]);
        if(!class_exists($bits[0]))
        {
            self::_json(array(
                'code' => 501,
                'message' => 'Module doesn\'t exist or doesn\'t implement an API class.'
            ));
        }

        if(!method_exists($module, $method))
        {
            self::_json(array(
                'code' => 501,
                'message' => 'Method doesn\'t exist in modules API class.'
            ));
        }

        $params = (count($bits) > 2) ? array_slice($bits, 2) : array();
        $params = array_merge(array($data), $params);

        $response = call_user_func_array(array($module, $method), $params);

        self::_json($response);
    }

    /**
     * Checks to ensure the standard fields are required in an API response and outputs to
     * the browser json encoded.
     */
    private static function _json($data)
    {
        if(!is_array($data))
        {
            self::_json(array(
                'code' => 400,
                'message' => 'API response was not in a valid array format.'
            ));
        }

        if(!isset($data['code']))
        {
            self::_json(array(
                'code' => 400,
                'message' => 'API response did not contain the required code argument.'
            ));
        }

        if(!isset($data['message']))
            $data['message'] = null;

        if(!isset($data['data']))
            $data['data'] = null;

        header(self::_getHeaderCode($data['code']));
        header('Content-type: application/json');

        die(json_encode($data));
    }

    /**
     * Sets the HTTP header based on the give $code. Supported codes and their response values are
     * managed in the API setup.php configs.
     */
    private static function _getHeaderCode($code)
    {
        $statusCodes = Config::get('api.status_codes');
        $message = (is_array($statusCodes) && isset($statusCodes[$code])) ? $statusCodes[$code] : '';

        return sprintf('HTTP/1.1 %d %s', $code, $message);
    }

}
