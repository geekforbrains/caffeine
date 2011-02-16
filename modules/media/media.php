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
	
	private static $_error = null;

	private static function error() {
		return self::$_error();
	}

	/**
	 * -------------------------------------------------------------------------
	 * Loads a dialog for TinyMCE based on the type (image, media, file). The
	 * block being loaded is "media_<type>_dialog".
	 * -------------------------------------------------------------------------
	 */
	public static function dialog($type) {
		View::load('Media', sprintf('media_%s_dialog', $type));
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
		// Check if file name is URL, probably youtube or vimeo link
		if(stristr('http://', $filename))
		{
			if($cid = Media_Model::create_url($filename))
				return $cid;
			else
				self::$_error = 'Error creating media URL. Please try again.';
		}

		// Check if filename exists in $_FILES as upload
		elseif(isset($_FILES[$filename]))
		{
			if($data = Upload::save($_FILES[$filename]))
			{
				if($cid = Media_Model::create_file($data))
					return $cid;
				else
					self::$_error = 'Error creating media file. Please try again.';
			}
			else
				self::$_error = Upload::error();
		}

		// Just incase something weird happens :P
		if(is_null(self::$_error))
			self::$_error = 'Unkown media error.';

		return false;
	}

}
