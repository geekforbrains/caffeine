<?php
session_start();

define('ROOT', __DIR__ . '/');
define('EXT', '.php');

define('ERROR_NOTFOUND', 404);
define('ERROR_ACCESSDENIED', 401);

$core = array(
    'module', // Module must be loaded first; everything extends it
    'config',
    'load',
    'site'
);

foreach($core as $module)
    require_once(ROOT . 'core/' . $module . '/' . $module . EXT);

spl_autoload_register('Load::auto');
Load::setupFiles();
Db::install();

Event::trigger('caffeine.started');
$data = Router::getRouteData();

if($data)
{
    list($module, $controller, $method) = $data['callback'];
    $params = Router::getParams();

    // Make sure controller words are upper
    $conBits = explode('_', $controller);
    foreach($conBits as &$bit)
        $bit = ucfirst($bit);
    $controller = implode('_', $conBits);

    $controller = sprintf('%s_%sController', ucfirst($module), ucwords($controller));
    $response = call_user_func_array(array($controller, $method), $params);

    // If the response is an int, assume its a pre-defined error code
    if(!is_int($response))
    {
        Event::trigger('module.response', array($response));
        View::load($module, $controller, $method);
    }

    // All other method responses are assumed to be errors. Load their views.
    else
        View::error($response);
}
else
    View::error(ERROR_NOTFOUND);

View::render();
Event::trigger('caffeine.finished');

Dev::outputDebug();
