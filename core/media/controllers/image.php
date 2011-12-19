<?php

class Media_ImageController extends Controller {

    
    /**
     * --------------------------------------------------------------------------- 
     * TODO
     * --------------------------------------------------------------------------- 
     */
    public static function render($id, $rotation = null, $widthOrPercent = null, $height = null)
    {
        $image = Media::m('file')->find($id);
        $path = Media::image()->render($id, $rotation, $widthOrPercent, $height);

        header('Content-Type: ' . $image->mime_type);
        readfile(ROOT . $path);
        die();
    }


}
