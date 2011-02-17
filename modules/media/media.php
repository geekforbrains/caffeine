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
	public static function dialog($type) 
	{
		$cid = null;

		if(isset($_FILES['media_file']))
		{
			if($cid = self::add('media_file'))
				Message::set(MSG_OK, 'File uploaded successfully.');
			else
				Message::set(MSG_ERR, self::$_error);
		}

		View::load('Media', sprintf('media_%s_dialog', $type), 
			array(
				'type' => $type,
				'cid' => $cid,
				'images' => Media_Model::get_all(MEDIA_TYPE_IMAGE)
			)		
		);
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
				$media_type = self::_determine_media_type($data['type']);

				if($cid = Media_Model::create_file($data, $media_type))
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
	public static function display($cid)
	{
		$file = Media_Model::get_file($cid);
		$path = UPLOAD_PATH . $file['path'] . $file['hash'];

		Imager::open($path);
		//Imager::resize(500, 500, true);
		Imager::percent(50);
		Imager::show();

		exit;
	}

	// TODO
	public static function download() {}

	// TODO
	private static function _determine_media_type($file_type)
	{
		if(stristr($file_type, 'image'))
			return MEDIA_TYPE_IMAGE;
		return MEDIA_TYPE_FILE;
	}

}
