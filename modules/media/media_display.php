<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
* =============================================================================
* Media_Display
* @author Gavin Vickery <gdvickery@gmail.com>
* @version 1.0
*
* The Media_Display class is used for displaying images and downloading files
* that are managed by the Media module. These methods are all typically called
* via the URL. It also handles displaying TinyMCE modules.
* =============================================================================
*/
class Media_Display {

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
			if($cid = Media::add('media_file'))
				Message::set(MSG_OK, 'File uploaded successfully.');
			else
				Message::set(MSG_ERR, self::$_error);
		}

		View::load('Media', sprintf('%s_dialog', $type), 
			array(
				'type' => $type,
				'cid' => $cid,
				'images' => Media_Model::get_all(MEDIA_TYPE_IMAGE)
			)		
		);
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

		$thumb_path = self::path($thumb_hash);
		$file_path = Upload::path($file['path'], $file['hash']);

		if(MEDIA_ENABLE_CACHE && file_exists($thumb_path))
		{
			Debug::log('Media', 'Loading image from cache: ' . $thumb_path);
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
			if(MEDIA_ENABLE_CACHE)
				Imager::save($thumb_path);

			Imager::show();
		}

		exit;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Returns the full file system path to media cache directory. Optionally
	 * a cached file can be passed as a param to cleanly return its full path.
	 * -------------------------------------------------------------------------
	 */
	public static function path($hash = null)
	{
		$user = User::current();
		$cache_path = $user['files_path'] . MEDIA_CACHE_DIR;

		if(!file_exists($cache_path))
			if(!mkdir($cache_path))
				die('Unable to create media cache directory: ' . $cache_path);

		if(!is_writable($cache_path))
			die('Media cache directory isn\'t writable: ' . $cache_path);

		return $cache_path . $hash;
	}

}
