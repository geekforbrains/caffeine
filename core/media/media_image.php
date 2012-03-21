<?php

class Media_Image extends Media_File {

    /**
     * Set the allowed image formats to be uploaded. Based on config in setup.php
     */
    public function __construct()
    {
        $this->_type = 'image';
        $this->_allowedExts = Config::get('media.allowed_image_formats');
    }

    /**
     * Has the same options as render, but instead of returning the system path
     * it returns a relative URL to the file.
     */
    public function getUrl($id, $rotation = null, $widthOrPercent = null, $height = null) {
        return Url::toLang(null, $this->render($id, $rotation, $widthOrPercent, $height));
    }

    /**
     * Alias of render.
     */
    public function getPath($id, $rotation = null, $widthOrPercent = null, $height = null) {
        return $this->render($id, $rotation, $widthOrPercent, $height);
    }

    /**
     * Returns the URL for a placeholder image (see image controller)
     */
    public function placeholder($width, $height) {
        return Url::to('media/placeholder/' . $width . '/' . $height);
    }

    /**
     * Gets an image by id sized and rotated based on the passed params. If the file
     * doesn't exist, it is created using the imager class and stored in cache dir. Every 
     * call after that will load the file directly.
     *
     * When sizing an image with the same width and height, it will be scaled down to the closest
     * width/height and then cropped.
     *
     * To size an image to a specific width, set the width value needed and the height value to 0. 
     * This ensures the specified width is met, and the height is adjusted proportionally. The same
     * can be done by setting the height needed and the width to 0.
     *
     * @param $id int The id of image to get
     * @param $rotation int The degrees to rotate the image, can be null
     * @param $widthOrPercent int When by itself, represents percent to increase/decrease size.
     *      When used with the height param, represents the image width.
     * @param $height int The height to size the image.
     *
     * @return string The relative path from ROOT to the file, if it exists. Boolean false otherwise.
     */
    public function render($id, $rotation = null, $widthOrPercent = null, $height = null)
    {
        try {
            if($image = Media::m('file')->find($id))
            {
                $ext = pathinfo($image->name, PATHINFO_EXTENSION);

                $cachedFilename = str_replace('.' . $ext, sprintf('_%d.%s', ($id . $rotation .$widthOrPercent . $height), $ext), $image->name);
                $cachedFullPath = ROOT . Media::getFilesPath() . Media::getCachePath() . $cachedFilename;

                if(!file_exists($cachedFullPath))
                {
                    $origFullPath = ROOT . Media::getFilesPath() . $image->path . $image->name;
                    Media_Imager::open($origFullPath);

                    if(!is_null($widthOrPercent) && is_null($height))
                        Media_Imager::percent($widthOrPercent);

                    elseif(!is_null($widthOrPercent) && !is_null($height))
                    {
                        $adaptive = ($widthOrPercent == 0 || $height == 0) ? false : true;
                        Media_Imager::resize($widthOrPercent, $height, $adaptive);
                    }

                    if(!is_null($rotation) && $rotation > 0)
                        Media_Imager::rotate($rotation);

                    Media_Imager::save($cachedFullPath);
                }

                return Media::getFilesPath() . Media::getCachePath() . $cachedFilename;
            }
        } catch(Exception $e) {
            Log::error('media', $e->getMessage());
        }

        return false;
    }

    /**
     * Saves an image posted from a multipart form.
     *
     * @param $name string The name of the field posted in $_FILES.
     *
     * @return object This current object. Used to check if there was an error
     *      or get the returned id.
     */
    public function save($name) {
        $response = Media_Uploader::save($name, 'image', Config::get('media.allowed_image_formats'));
        
        if(!$response)
            $this->_error = Media_Uploader::getError();
        else
            $this->_id = $response;

        return $this;
    }

    /**
     * TODO Comments.
     */
    public function saveBinary($filename, $binary, $mimeType)
    {
        $response = Media_Uploader::saveBinary($filename, $binary, $mimeType, 'image');

        if(!$response)
            $this->_error = Media_Uploader::getError();
        else
            $this->_id = $response;

        return $this;
    }

    /**
     * Reads an image from the given url and stores it on disk.
     */
    public function saveFromUrl($url) {
        $response = Media_Uploader::saveFromUrl($url, 'image');

        if(!$response)
            $this->_error = Media_Uploader::getError();
        else
            $this->_id = $response;

        return $this;
    }

}
