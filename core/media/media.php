<?php

class Media extends Module {


    /**
     * --------------------------------------------------------------------------- 
     * Helper method for getting new Media_Image instance.
     * --------------------------------------------------------------------------- 
     */
    public static function image() {
        return new Media_Image();
    }


    /**
     * --------------------------------------------------------------------------- 
     * Helper method for getting new Media_Video instance.
     * --------------------------------------------------------------------------- 
     */
    public static function video() {
        return new Media_Video();
    }


    /**
     * --------------------------------------------------------------------------- 
     * Helper method for getting new Media_File instance.
     * --------------------------------------------------------------------------- 
     */
    public static function file() {
        return new Media_File();
    }


    /**
     * --------------------------------------------------------------------------- 
     * Returns the relative path from within the files directory to the media directory.
     * --------------------------------------------------------------------------- 
     */
    public static function getMediaPath()
    {
        $mediaDir = Config::get('media.media_dir');
        $mediaFullPath = ROOT . self::getFilesPath() . $mediaDir;

        if(!file_exists($mediaFullPath))
        {
            if(!is_writable(ROOT . self::getFilesPath()) || !mkdir($mediaFullPath, 0777, true))
                return false;
        }

        return $mediaDir;
    }


    /**
     * --------------------------------------------------------------------------- 
     * Returns the relative path from within the files directory to the cache directory.
     * --------------------------------------------------------------------------- 
     */
    public static function getCachePath()
    {
        $cacheDir = Config::get('media.cache_dir');
        $cacheFullPath = ROOT . self::getFilesPath() . $cacheDir;

        if(!file_exists($cacheFullPath))
        {
            if(!is_writable($cacheFullPath) || !mkdir($cacheFullPath, 0777, true))
                return false;
        }

        return $cacheDir;
    }


    /**
     * --------------------------------------------------------------------------- 
     * Returns the relative path from ROOT to the current sites files directory.
     * --------------------------------------------------------------------------- 
     */
    public static function getFilesPath() {
        return Site::getRelativePath() . Config::get('media.files_dir');
    }


}
