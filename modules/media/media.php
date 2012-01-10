<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
* =============================================================================
* Media
* @author Gavin Vickery <gdvickery@gmail.com>
* @version 1.0
*
* The media module handles all images, videos and other files for other areas
* of the application. Its only purpose is to provide a uniform way to upload
* store and retrieve different file types.
*
* This module makes use of the "Upload" module.
* =============================================================================
*/
class Media {
	
	// Stores any errors returned by Upload module, or within Media
	private static $_error = null;

	public static function error() {
		return self::$_error;
	}

    // Get the relative url to a given file name
    public static function fileUrl($file)
    {
		$user = User::current();
		$cache_path = $user['files_path'] . MEDIA_CACHE_DIR;
        $relative = str_replace(CAFFEINE_ROOT, '', $cache_path);

        return Router::url($relative . $file);
    }

    /**
     * Returns the full url to an image file. If the image with the given dimensions/rotation
     * doesn't exist, it is created first. Every time after that the file is returned directly.
     */
	public static function image($cid, $rotate = 0, $wp = null, $h = null)
    {
		$file = Media_Model::get_file($cid);
		$thumb_hash = md5($cid . $rotate . $wp . $h);

        // Append extension based on type
        switch($file['type'])
        {
            case 'image/gif':
                $thumb_hash .= '.gif';
                break;

            case 'image/png':
                $thumb_hash .= '.png';
                break;

            default:
                $thumb_hash .= '.jpg';
                break;
        }

		$thumb_path = Media_Display::path($thumb_hash);

		if(!file_exists($thumb_path))
		{
		    $file_path = Upload::path($file['path'], $file['hash']);

			Imager::open($file_path);

			// Check for resize by percent
			if($wp > 0 && is_null($h))
				Imager::percent($wp); // Not actually width, used as percent in this case

			// If width and height are set, do adapative resize
			elseif($wp > 0 && $h > 0)
				Imager::resize($wp, $h, true);

			// Regular resize, based on highest value
			elseif($wp > 0 || $h > 0)
				Imager::resize($wp, $h, false);

			// Check for rotate
			if($rotate > 0)
				Imager::rotate($rotate);
	
			// Save thumb for caching and display
			if(MEDIA_ENABLE_CACHE)
				Imager::save($thumb_path);
		}

        return self::fileUrl($thumb_hash);
    }

	/**
	 * -------------------------------------------------------------------------
	 * Used for uploading types of media. Files are stored as images, videos
	 * or regular files. Types are determined by extension or URL.
	 *
	 * @param $filename
	 *		The name of the file uploaded (ie: in $_FILES[$filename]) or the URL
	 *		to a source such as youtube or vimeo.
	 *
	 * @return mixed
	 *		If a type was determined and was added successfully, a content ID
	 *		is returned. Otherwise boolean false is returned and an error is
	 *		set. The error can be retrieved via Media::error();
	 * -------------------------------------------------------------------------
	 */
	public static function add($filename)
	{
		// Check if filename exists in $_FILES as upload
		if(isset($_FILES[$filename]))
		{
			if($data = Upload::save($_FILES[$filename]))
			{
				$media_type = self::determine_media_type($data['type']);
				$exif = self::_read_exif($media_type, $data);

				if($cid = Media_Model::create_file($data, $media_type, $exif))
					return $cid;
				else
					self::$_error = 'Error creating media file. Please try again.';
			}
			else
				self::$_error = Upload::error();
		}

		// Check if file name is URL, probably youtube or vimeo link
		elseif(is_string($filename) && stristr('http://', $filename))
		{
			if($cid = Media_Model::create_url($filename))
				return $cid;
			else
				self::$_error = 'Error creating media URL. Please try again.';
		}

		// Just incase something weird happens :P
		if(is_null(self::$_error))
			self::$_error = 'Unkown media error.';

		return false;
	}

    /**
     * Same as Media::add except saves a file from the given url. Mostly used for images.
     */
    public static function add_from_url($url)
    {
        if($data = Upload::save_url($url))
        {
            $media_type = self::determine_media_type($data['type']);
            $exif = self::_read_exif($media_type, $data);

            if($cid = Media_Model::create_file($data, $media_type, $exif))
                return $cid;
            else
                self::$_error = 'Error creating media file. Please try again.';
        }
        else
            self::$_error = Upload::error();

        return false;
    }

	public static function delete($cid)
	{
		$file = Media_Model::get_file($cid);
		@unlink(Upload::path($file['path'], $file['hash']));
		return Media_Model::delete($cid);
	}

	// TODO
	// Determine if type is file or url
	public static function get($cid) {
		return Media_Model::get_file($cid);
	}

	/**
	 * -------------------------------------------------------------------------
	 * Get all files or all files of a specific type.
	 * -------------------------------------------------------------------------
	 */
	public static function get_all($type = null) {
		return Media_Model::get_all($type);
	}

	// TODO
	public static function determine_media_type($file_type)
	{
		if(stristr($file_type, 'image'))
			return MEDIA_TYPE_IMAGE;
		return MEDIA_TYPE_FILE;
	}

	// TODO
	private static function _read_exif($type, $data)
	{
		// Exif unstable, just return empty for now
		return null;

		if($type == MEDIA_TYPE_IMAGE)
		{
			$file = Upload::path($data['path'], $data['hash']);

			// Apparently have to catch incase exif is fucked
			try {
				if($data = @exif_read_data($file))
					$exif = $data;
			} catch(Exception $e) {
				$exif = null;
			}

			if($exif)
				return serialize($exif);
		}

		return null;
	}


}
