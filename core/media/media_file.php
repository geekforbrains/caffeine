<?php

class Media_File {

    /**
     * Returns the relative url to a file.
     */
    public function get($id) {

    }

    /**
     * Returns an array of file details.
     */
    public function getDetails($id) {
        return Media::getDetails($id);
    }

    /**
     * Saves a file that exists in $_FILES
     */
    public function save($name) {
        return Media_Uploader::save($name, 'file', Config::get('media.allowed_file_formats'));
    }

    /**
     * Reads a file from the given url and stores it on disk.
     */
    public function saveFromUrl($url) {

    }

}
