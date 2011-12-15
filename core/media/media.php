<?php

// TODO Save an uploaded image/file and get its full path (resized images if need be, should be cached)
// TODO Save a file or image from a url with same functionality as above
// TODO Save a video from a supported url (youtube, vimeo), and get video details, output video html with sizes
class Media extends Module {

    /**
     * Helper method for getting new image instance.
     */
    public static function image() {
        return new Media_Image();
    }

    /**
     * Helper method for getting new video instance.
     */
    public static function video() {
        return new Media_Video();
    }

    /**
     * Helper method for getting new file instance.
     */
    public static function file() {
        return new Media_File();
    }

    public function getDetails($id) {}

    /**
     * Returns the relative path from within the files directory to the media directory.
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
     * Returns the relative path from within the files directory to the cache directory.
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
     * Returns the relative path from ROOT to the current sites files directory.
     */
    public static function getFilesPath() {
        return Site::getRelativePath() . Config::get('media.files_dir');
    }

}
