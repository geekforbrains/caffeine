<?php

class Media_File {

    /**
     * The current uploaded file id, if any.
     */
    protected $_id = 0;
    
    /**
     * The current upload error, if any.
     */
    protected $_error = null;

    /**
     * Store the type as a property so we can extend this class
     */
    protected $_type = 'file';

    /**
     * Store the allowed file extensions for the type so we can extend this class.
     */
    protected $_allowedExts = array();

    /**
     * Set the allowed image formats to be uploaded. Based on config in setup.php
     */
    public function __construct() {
        $this->_allowedExts = Config::get('media.allowed_file_formats');
    }

    /**
     * Checks if the uploaded file has an error. Returns boolean.
     */
    public function hasError()
    {
        if(!is_null($this->_error))
            return true;
        return false;
    }

    /**
     * Returns the error, if any, from the file uploaded.
     */
    public function getError() {
        return $this->_error;
    }

    /**
     * Returns the id of the uploaded file. Will be 0 if the upload failed.
     */
    public function getId() {
        return $this->_id;
    }

    /**
     * Returns the relative URL to the file.
     */
    public function getUrl($id, $includeBase = true)
    {
        if($path = $this->getPath($id))
        {
            if($includeBase)
                return Url::toLang(null, $path); // Ignore language codes when getting full file URLs
            else
                return $path;
        }

        return false;
    }

    /**
     * Returns the relative path from ROOT to the file.
     */
    public function getPath($id)
    {
        if($file = Media::m('file')->find($id))
        {
            $path = Media::getFilesPath() . $file->path . $file->name;
            if(file_exists($path))
                return $path;
        }

        return false;
    }

    /**
     * Checks if a file was uploaded. Returns boolean.
     */
    public function wasUploaded($filename) {
        return is_uploaded_file($_FILES[$filename]['tmp_name']);
    }

    /**
     * Saves an file posted from a multipart form.
     *
     * @param $name string The name of the field posted in $_FILES.
     *
     * @return object This current object. Used to check if there was an error
     *      or get the returned id.
     */
    public function save($name)
    {
        $response = Media_Uploader::save($name, $this->_type, $this->_allowedExts);
        
        if(!$response)
            $this->_error = Media_Uploader::getError();
        else
            $this->_id = $response;

        return $this;
    }

    /**
     * Reads an file from the given url and stores it on disk.
     */
    public function saveFromUrl($url) {
        $response = Media_Uploader::saveFromUrl($url, 'file');

        if(!$response)
            $this->_error = Media_Uploader::getError();
        else
            $this->_id = $response;

        return $this;
    }

}
