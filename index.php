<?php
echo "<pre>";

define('ROOT', __DIR__ . '/');
define('EXT', '.php');

define('ERROR_NOTFOUND', 404);
define('ERROR_ACCESSDENIED', 401);

$core = array(
    'module', // Module must be loaded first; everything extends it
    'config',
    'load',
    'site',
    'router'
);

foreach($core as $module)
    require_once(ROOT . 'modules/core/' . $module . '/' . $module . EXT);

spl_autoload_register('Load::auto');
Load::setupFiles();
Db::install();

$data = Router::getRouteData();

if($data)
{
    // example.com/blog/admin/posts
    // look for controller, working backwards
    // blog/controllers/admin/posts.php
    // blog/controllers/admin.php::posts

    // one/two/three/four/five
    // modules/one/controllers/two/three/four/five.php
    // modules/one/controllers/two/three/four.php (five)
    // modules/one/controllers/two/three.php (four, five)
    // modules/one/controllers/two (three, four, five) -> call _default
    die(print_r($data, true));

    list($module, $controller, $method, $params) = $data;

    // TODO Before calling, check if user has permission based on Controller::_permissions method
    $controller = sprintf('%s_%sController', ucfirst($module), ucfirst($controller));

    if(method_exists($controller, $method))
    {
        $response = call_user_func_array(array($controller, $method), $params);

        // If a method doesn't return anything, or returns boolean true, load its view
        if(is_null($response) || $response === true)
            View::load($module, $controller, $method);

        // All other method responses are assumed to be errors. Load their views.
        else
            View::error($response);
    }
    else
        View::error(ERROR_NOTFOUND);
}
else
    View::error(ERROR_NOTFOUND);

View::render();
echo "</pre>";
