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

	/**
	 * ------------------------------------------------------------------------
	 * Displays images based on parameters. Usually these are called via the
	 * URL, but you can technically call this method Media::image() manually
	 * as well.
	 *
	 * @param $cid
	 *		The content ID of the image to display. This ID must exist within
	 *		the media module (ie: uploaded using the media module). If only
	 *		this param is set, the original image is displayed.
	 *
	 * @param $wp
	 *		This param has two different functions. If only the $cid and this
	 *		param are passed, then this value is used as a resize by percent.
	 *		However, if all three params are set, $cid, $wp and $h this param
	 *		is considered a "width" setting. When width and height are set
	 *		either a regular resize takes place, or an adaptive resize. See 
	 *		below for more info.
	 *
	 * @param $h
	 *		Used for setting the height when resizing.
	 * -------------------------------------------------------------------------
	 */
	public static function image($cid, $rotate = 0, $wp = null, $h = null)
	{
		$file = Media_Model::get_file($cid);
		$thumb_hash = md5($cid . $rotate . $wp . $h);

		$thumb_path = MEDIA_CACHE . $thumb_hash;
		$file_path = Upload::path($file['path'], $file['hash']);

		if(file_exists($thumb_path))
		{
			header('Content-type: ' . $file['type']);
			readfile($thumb_path);
		}
		else
		{
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
			//Imager::save($thumb_path);
			Imager::show();
		}

		exit;
	}

	// TODO
	private static function _determine_media_type($file_type)
	{
		if(stristr($file_type, 'image'))
			return MEDIA_TYPE_IMAGE;
		return MEDIA_TYPE_FILE;
	}

}
