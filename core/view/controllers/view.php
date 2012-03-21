<?php 

class View_ViewController extends Controller {

    public static function js($cacheKey)
    {
        ob_start('ob_gzhandler');

        header('Content-type: text/javascript; charset: UTF-8');
        header('Cache-Control: must-revalidate');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');

        if($content = Cache::get($cacheKey))
            echo $content;

        die();
    }

    public static function css($cacheKey)
    {
        ob_start('ob_gzhandler');

        header('Content-type: text/css; charset: UTF-8');
        header('Cache-Control: must-revalidate');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');

        if($content = Cache::get($cacheKey))
            echo $content;

        die();
    }

}
