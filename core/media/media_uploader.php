<?php

class Media_Uploader {


    /**
     * --------------------------------------------------------------------------- 
     * TODO
     * --------------------------------------------------------------------------- 
     */
    private static $_error = null;


    /**
     * --------------------------------------------------------------------------- 
     * TODO
     * --------------------------------------------------------------------------- 
     */
    private static $_mimeTypes = array(
		'323' => 'text/h323',
		'acx' => 'application/internet-property-stream',
		'ai' => 'application/postscript',
		'aif' => 'audio/x-aiff',
		'aifc' => 'audio/x-aiff',
		'aiff' => 'audio/x-aiff',
		'asf' => 'video/x-ms-asf',
		'asr' => 'video/x-ms-asf',
		'asx' => 'video/x-ms-asf',
		'au' => 'audio/basic',
		'avi' => 'video/x-msvideo',
		'axs' => 'application/olescript',
		'bas' => 'text/plain',
		'bcpio' => 'application/x-bcpio',
		'bin' => 'application/octet-stream',
		'bmp' => 'image/bmp',
		'c' => 'text/plain',
		'cat' => 'application/vnd.ms-pkiseccat',
		'cdf' => 'application/x-cdf',
		'cer' => 'application/x-x509-ca-cert',
		'class' => 'application/octet-stream',
		'clp' => 'application/x-msclip',
		'cmx' => 'image/x-cmx',
		'cod' => 'image/cis-cod',
		'cpio' => 'application/x-cpio',
		'crd' => 'application/x-mscardfile',
		'crl' => 'application/pkix-crl',
		'crt' => 'application/x-x509-ca-cert',
		'csh' => 'application/x-csh',
		'css' => 'text/css',
		'dcr' => 'application/x-director',
		'der' => 'application/x-x509-ca-cert',
		'dir' => 'application/x-director',
		'dll' => 'application/x-msdownload',
		'dms' => 'application/octet-stream',
		'doc' => 'application/msword',
		'dot' => 'application/msword',
		'dvi' => 'application/x-dvi',
		'dxr' => 'application/x-director',
		'eps' => 'application/postscript',
		'etx' => 'text/x-setext',
		'evy' => 'application/envoy',
		'exe' => 'application/octet-stream',
		'fif' => 'application/fractals',
		'flr' => 'x-world/x-vrml',
		'gif' => 'image/gif',
		'gtar' => 'application/x-gtar',
		'gz' => 'application/x-gzip',
		'h' => 'text/plain',
		'hdf' => 'application/x-hdf',
		'hlp' => 'application/winhlp',
		'hqx' => 'application/mac-binhex40',
		'hta' => 'application/hta',
		'htc' => 'text/x-component',
		'htm' => 'text/html',
		'html' => 'text/html',
		'htt' => 'text/webviewhtml',
		'ico' => 'image/x-icon',
		'ief' => 'image/ief',
		'iii' => 'application/x-iphone',
		'ins' => 'application/x-internet-signup',
		'isp' => 'application/x-internet-signup',
		'jfif' => 'image/pipeg',
		'jpe' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpg' => 'image/jpeg',
		'js' => 'application/x-javascript',
		'latex' => 'application/x-latex',
		'lha' => 'application/octet-stream',
		'lsf' => 'video/x-la-asf',
		'lsx' => 'video/x-la-asf',
		'lzh' => 'application/octet-stream',
		'm13' => 'application/x-msmediaview',
		'm14' => 'application/x-msmediaviw',
		'm3u' => 'audio/x-mpegurl',
		'man' => 'application/x-troff-man',
		'mdb' => 'application/x-msaccess',
		'me' => 'application/x-troff-me',
		'mht' => 'message/rfc822',
		'mhtml' => 'message/rfc822',
		'mid' => 'audio/mid',
		'mny' => 'application/x-msmoney',
		'mov' => 'video/quicktime',
		'movie' => 'video/x-sgi-movie',
		'mp2' => 'video/mpeg',
		'mp3' => 'audio/mpeg',
		'mpa' => 'video/mpeg',
		'mpe' => 'video/mpeg',
		'mpeg' => 'video/mpeg',
		'mpg' => 'video/mpeg',
		'mpp' => 'application/vnd.ms-project',
		'mpv2' => 'video/mpeg',
		'ms' => 'application/x-troff-ms',
		'mvb' => 'application/x-msmediaview',
		'nws' => 'message/rfc822',
		'oda' => 'application/oda',
		'p10' => 'application/pkcs10',
		'p12' => 'application/x-pkcs12',
		'p7b' => 'application/x-pkcs7-certificates',
		'p7c' => 'application/x-pkcs7-mime',
		'p7m' => 'application/x-pkcs7-mime',
		'p7r' => 'application/x-pkcs7-certreqresp',
		'p7s' => 'application/x-pkcs7-signature',
		'pbm' => 'image/x-portable-bitmap',
		'pdf' => 'application/pdf',
		'pfx' => 'application/x-pkcs12',
		'pgm' => 'image/x-portable-graymap',
		'pko' => 'application/ynd.ms-pkipko',
		'pma' => 'application/x-perfmon',
		'pmc' => 'application/x-perfmon',
		'pml' => 'application/x-perfmon',
		'pmr' => 'application/x-perfmon',
		'pmw' => 'application/x-perfmon',
		'png' => 'image/png',
		'pnm' => 'image/x-portable-anymap',
		'pot' => 'application/vnd.ms-powerpoint',
		'ppm' => 'image/x-portable-pixmap',
		'pps' => 'application/vnd.ms-powerpoint',
		'ppt' => 'application/vnd.ms-powerpoint',
		'prf' => 'application/pics-rules',
		'ps' => 'application/postscript',
		'pub' => 'application/x-mspublisher',
		'qt' => 'video/quicktime',
		'ra' => 'audio/x-pn-realaudio',
		'ram' => 'audio/x-pn-realaudio',
		'ras' => 'image/x-cmu-raster',
		'rgb' => 'image/x-rgb',
		'rmi' => 'audio/mid',
		'roff' => 'application/x-troff',
		'rtf' => 'application/rtf',
		'rtx' => 'text/richtext',
		'scd' => 'application/x-msschedule',
		'sct' => 'text/scriptlet',
		'setpay' => 'application/set-payment-initiation',
		'setreg' => 'application/set-registration-initiation',
		'sh' => 'application/x-sh',
		'shar' => 'application/x-shar',
		'sit' => 'application/x-stuffit',
		'snd' => 'audio/basic',
		'spc' => 'application/x-pkcs7-certificates',
		'spl' => 'application/futuresplash',
		'src' => 'application/x-wais-source',
		'sst' => 'application/vnd.ms-pkicertstore',
		'stl' => 'application/vnd.ms-pkistl',
		'stm' => 'text/html',
		'svg' => 'image/svg+xml',
		'sv4cpio' => 'application/x-sv4cpio',
		'sv4crc' => 'application/x-sv4crc',
		'swf' => 'application/x-shockwave-flash',
		't' => 'application/x-troff',
		'tar' => 'application/x-tar',
		'tcl' => 'application/x-tcl',
		'tex' => 'application/x-tex',
		'texi' => 'application/x-texinfo',
		'texinfo' => 'application/x-texinfo',
		'tgz' => 'application/x-compressed',
		'tif' => 'image/tiff',
		'tiff' => 'image/tiff',
		'tr' => 'application/x-troff',
		'trm' => 'application/x-msterminal',
		'tsv' => 'text/tab-separated-values',
		'txt' => 'text/plain',
		'uls' => 'text/iuls',
		'ustar' => 'application/x-ustar',
		'vcf' => 'text/x-vcard',
		'vrml' => 'x-world/x-vrml',
		'wav' => 'audio/x-wav',
		'wcm' => 'application/vnd.ms-works',
		'wdb' => 'application/vnd.ms-works',
		'wks' => 'application/vnd.ms-works',
		'wmf' => 'application/x-msmetafile',
		'wps' => 'application/vnd.ms-works',
		'wri' => 'application/x-mswrite',
		'wrl' => 'x-world/x-vrml',
		'wrz' => 'x-world/x-vrml',
		'xaf' => 'x-world/x-vrml',
		'xbm' => 'image/x-xbitmap',
		'xla' => 'application/vnd.ms-excel',
		'xlc' => 'application/vnd.ms-excel',
		'xlm' => 'application/vnd.ms-excel',
		'xls' => 'application/vnd.ms-excel',
		'xlt' => 'application/vnd.ms-excel',
		'xlw' => 'application/vnd.ms-excel',
		'xof' => 'x-world/x-vrml',
		'xpm' => 'image/x-xpixmap',
		'xwd' => 'image/x-xwindowdump',
		'z' => 'application/x-compress',
		'zip' => 'application/zip'
	);


    /**
     * --------------------------------------------------------------------------- 
     * TODO
     * --------------------------------------------------------------------------- 
     */
    public static function getError() {
        return self::$_error;
    }


    /**
     * --------------------------------------------------------------------------- 
     * Saves the given file and returns the id if successful otherwise boolean false
     *
     * Types are: file, image, video
     * --------------------------------------------------------------------------- 
     */
    public static function save($name, $type, $allowed_formats = array())
    {
        self::$_error = null;
        $file = $_FILES[$name];

		if($file['error'] > 0)
		{
			self::$_error = self::_determineError($file['error']);
			return false;
		}

        $filesPath = Media::getFilesPath();
        if(!$filesPath)
        {
            self::$_error = 'Files directory doesn\'t exist or isn\'t writable.';
            return false;
        }

        $mediaPath = Media::getMediaPath();
        if(!$mediaPath)
        {
            self::$_error = 'Media directory doesn\'t exist or isn\'t writable.';
            return false;
        }

        $uploadPath = $filesPath . $mediaPath;
        $newFilename = self::_getAvailFilename($file['name'], $uploadPath);
        $fullPath = ROOT . $uploadPath . $newFilename;

        if(!strlen($file['type']))
            $file['type'] = 'text/plain';

        if(move_uploaded_file($file['tmp_name'], $fullPath))
        {
            $id = Media::m('file')->insert(array(
                'name' => $newFilename,
                'path' => $mediaPath,
                'size' => $file['size'],
                'mime_type' => $file['type'],
                'file_type' => $type
            ));

            if($id)
                return $id;
            else
            {
                self::$_error = 'Error saving uploaded file to database.';

                // If the file was uploaded, but the db failed, remove the file
                if(file_exists($fullPath))
                    @unlink($fullPath);

                return false;
            }
        }

        self::$_error = 'Unkown media error occured.';
	    return false;
    }


    public static function saveBinary($filename, $binary, $mimeType, $fileType)
    {
        $filesPath = Media::getFilesPath();
        $mediaPath = Media::getMediaPath();
        $uploadPath = $filesPath . $mediaPath;

        $newFilename = self::_getAvailFilename($filename, $uploadPath);
        $fullPath = ROOT . $uploadPath . $newFilename;

        $fp = fopen($fullPath, 'w');
        fwrite($fp, $binary);
        fclose($fp); 

        $id = Media::m('file')->insert(array(
            'name' => $newFilename,
            'path' => Media::getMediaPath(), // Must be relative because the site directory could change
            'size' => filesize($fullPath),
            'mime_type' => $mimeType,
            'file_type' => $fileType
        ));

        return $id;
    }


    /**
     * --------------------------------------------------------------------------- 
     * TODO
     * --------------------------------------------------------------------------- 
    public static function saveFromUrl($url)
    {
        $file_hash = md5($url);
        $file_path = self::_determine_upload_path(); // YYYY/MM/
        $full_path = self::path($file_path, $file_hash);  // ROOT/uploads/YYYY/MM/[hash]

        if(file_put_contents($full_path, file_get_contents($url)))
        {
            $bits = explode('/', $url);

            return array(
                'name' => $bits[count($bits) - 1],
                'hash' => $file_hash,
                'path' => $file_path,
                'type' => self::_determineMimeType($url),
                'size' => filesize($full_path),
                'full_path' => $full_path
            );
        }
        else
            self::$_error = 'Unable to save file from URL.';

        return false;
    }
    */


    /**
     * --------------------------------------------------------------------------- 
     * Checks if the given file already exists in the given path. If it does, loop
     * the name appending an incremented number to the end until an available filename
     * is found.
     *
     * ex: myfile.jpg, myfile-1.jpg, myfile-2.jpg etc.
     * --------------------------------------------------------------------------- 
     */
    private static function _getAvailFilename($filename, $uploadPath)
    {
        if(file_exists($uploadPath . $filename))
        {
            $count = 1;
            $ext = pathinfo($filename, PATHINFO_EXTENSION);

            while(true)
            {
                $tmpName = str_replace('.' . $ext, sprintf('-%d.%s', $count, $ext), $filename);

                if(!file_exists($uploadPath . $tmpName))
                    return $tmpName;

                $count++;
            }
        }

        return $filename;
    }


    /**
     * --------------------------------------------------------------------------- 
     * TODO
     * --------------------------------------------------------------------------- 
     */
    private static function _determineMimeType($path)
	{
		$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

		if(isset(self::$_mime_types[$ext]))
			return self::$_mime_types[$ext];

		return 'application/octet-stream';
	}


    /**
     * --------------------------------------------------------------------------- 
     * TODO
     * --------------------------------------------------------------------------- 
     */
	private static function _determineError($errorCode)
	{
		switch($errorCode)
		{
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				return 'The uploaded file exceeds the maximum size.';

			case UPLOAD_ERR_PARTIAL:
				return 'The file was only partially uploaded.';

			case UPLOAD_ERR_NO_FILE:
				return 'No file was uploaded.';

			case UPLOAD_ERR_NO_TMP_DIR:
				return 'No temporary directory exists for file uploads.';

			case UPLOAD_ERR_CANT_WRITE:
				return 'Failed to write upload to disk.';

			default:
				return 'Unkown upload error.';
		}
	}


}
