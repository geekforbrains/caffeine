<?php

class Media extends Module {

    /**
     * Helper method for getting new Media_Image instance.
     */
    public static function image() {
        return new Media_Image();
    }

    /**
     * Helper method for getting new Media_Video instance.
     */
    public static function video() {
        return new Media_Video();
    }

    /**
     * Helper method for getting new Media_File instance.
     */
    public static function file() {
        return new Media_File();
    }

    /**
     * Deletes the file associated with the given id from the system.
     *
     * @param $id int The id of the file to delete.
     * @return boolean
     */
    public static function delete($id)
    {
        if($file = Media::m('file')->find($id))
        {
            $filePath = ROOT . self::getFilesPath() . $file->path . $file->name;

            if(file_exists($filePath))
                @unlink($filePath);

            self::clearCache($file);

            if(Media::m('file')->delete($id))
                return true;
        }

        return false;
    }

    /**
     * Clears all cached images for the given image file.
     *
     * @param $file object The file object to clear all cached images for
     * @return int The number of files cleared.
     */
    public static function clearCache($file)
    {
        $count = 0;
        $cachePath = ROOT . self::getFilesPath() . self::getCachePath();

        if(file_exists($cachePath))
        {
            $rawName = substr($file->name, 0, -4); // Get name without (.jpg/.gif/.png etc)
            $items = scandir($cachePath);

            foreach($items as $i)
            {
                if(substr($i, 0, strlen($rawName)) == $rawName) // If the first part of the cached file matches original file, its a cache of it
                {
                    @unlink($cachePath . $i);
                    $count++;
                }
            }
        }

        return $count;
    }

    /**
     * Returns the relative path from within the files directory to the media directory.
     */
    public static function getMediaPath()
    {
        $mediaDir = Config::get('media.media_dir');
        $mediaFullPath = ROOT . self::getFilesPath() . $mediaDir;

        if(!file_exists($mediaFullPath))
        {
            if(!is_writable(ROOT . self::getFilesPath()) || !mkdir($mediaFullPath, Config::get('media.dir_chmod'), true))
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
            if(!is_writable(ROOT . self::getFilesPath()) || !mkdir($cacheFullPath, Config::get('media.dir_chmod'), true))
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
