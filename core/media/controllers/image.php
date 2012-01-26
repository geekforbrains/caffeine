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


    /**
     * --------------------------------------------------------------------------- 
     * Placeholder code adapted from dummyimage.com
     * --------------------------------------------------------------------------- 
     */
    public static function placeholder($width, $height)
    {
        $file_format = 'gif';
        $width = $width;
        $height = $height;

        $text_angle = 0;
        $font = Load::getModulePath('media') . 'assets/mplus-1c-medium.ttf';

        $img = imageCreate($width,$height);
        $bg_color = imageColorAllocate($img, 196, 196, 196);
        $fg_color = imageColorAllocate($img, 94, 94, 94);


        $lines = 1;
        $text = $width . 'x' . $height;

        $fontsize = max(min($width/strlen($text)*1.15, $height*0.5) ,5);
        $textBox = self::_imagettfbbox_t($fontsize, $text_angle, $font, $text);

        $textWidth = ceil(($textBox[4] - $textBox[1]) * 1.07);
        $textHeight = ceil( (abs($textBox[7])+abs($textBox[1])) * 1 );

        $textX = ceil(($width - $textWidth) / 2);
        $textY = ceil(($height - $textHeight) / 2 + $textHeight);

        imageFilledRectangle($img, 0, 0, $width, $height, $bg_color);
        imagettftext($img, $fontsize, $text_angle, $textX, $textY, $fg_color, $font, $text);


        $offset = 60 * 60 * 24 * 14; //14 Days
        $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($ExpStr); //Set a far future expire date. This keeps the image locally cached by the user for less hits to the server.
        header('Cache-Control:	max-age=120');
        header("Last-Modified: " . gmdate("D, d M Y H:i:s", time() - $offset) . " GMT");
        header('Content-type: image/'.$file_format); //Set the header so the browser can interpret it as an image and not a bunch of weird text.

        switch ($file_format)
        {
            case 'gif':
                imagegif($img);
                break;
            case 'png':
               imagepng($img);
                break;
            case 'jpg':
                imagejpeg($img);
                break;
            case 'jpeg':
                imagejpeg($img);
                break;
        }


        imageDestroy($img);
        exit();
    }


    private static function _imagettfbbox_t($size, $text_angle, $fontfile, $text)
    {
        // compute size with a zero angle
        $coords = imagettfbbox($size, 0, $fontfile, $text);
        
        // convert angle to radians
        $a = deg2rad($text_angle);
        
        // compute some usefull values
        $ca = cos($a);
        $sa = sin($a);
        $ret = array();
        
        // perform transformations
        for($i = 0; $i < 7; $i += 2){
            $ret[$i] = round($coords[$i] * $ca + $coords[$i+1] * $sa);
            $ret[$i+1] = round($coords[$i+1] * $ca - $coords[$i] * $sa);
        }
        return $ret;
    }


}
