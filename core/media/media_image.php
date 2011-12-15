<?php

class Media_Image {

    private $_id = 0;
    private $_error = null;

    public function hasError()
    {
        if(!is_null($this->_error))
            return true;
        return false;
    }

    public function getError() {
        return $this->_error;
    }

    public function getId() {
        return $this->_id;
    }

    public function get($id, $rotation = null, $widthOrPercent = null, $height = null) {
        return Url::to($this->render($id, $rotation, $widthOrPercent, $height));
    }

    public function render($id, $rotation = null, $widthOrPercent = null, $height = null)
    {
        if($image = Media::m('file')->find($id))
        {
            $ext = pathinfo($image->name, PATHINFO_EXTENSION);

            $cachedFilename = str_replace('.' . $ext, sprintf('_%d.%s', ($id . $rotation .$widthOrPercent . $height), $ext), $image->name);
            $cachedFullPath = ROOT . Media::getFilesPath() . Media::getCachePath() . $cachedFilename;

            // Save file as <origname>_widthxheightxrotation.ext
            if(!file_exists($cachedFullPath))
            {
                $origFullPath = ROOT . Media::getFilesPath() . $image->path . $image->name;
                Media_Imager::open($origFullPath);

                if(!is_null($widthOrPercent) && is_null($height))
                    Media_Imager::percent($widthOrPercent);

                elseif(!is_null($widthOrPercent) && !is_null($height))
                    Media_Imager::resize($widthOrPercent, $height, ($widthOrPercent == $height) ? true : false);

                if(!is_null($rotation) && $rotation > 0)
                    Media_Imager::rotate($rotation);

                Media_Imager::save($cachedFullPath);
            }

            return Media::getFilesPath() . Media::getCachePath() . $cachedFilename;
        }
    }

    /**
     * Saves an image that exists in $_FILES
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
     * Reads an image from the given url and stores it on disk.
     */
    public function saveFromUrl($url) {

    }

}
