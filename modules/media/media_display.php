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
	 * -------------------------------------------------------------------------
	 * Forces the given media CID to be downloaded through the browser.
	 * -------------------------------------------------------------------------
	 */
	public static function download($cid)
	{
		$file = Media_Model::get_file($cid);

		if($file)
		{
			$file_path = Upload::path($file['path'], $file['hash']);

			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' .$file['name']);
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' .$file['size']);

			ob_clean();
			flush();

			readfile($file_path);
			exit;
		}

		return false;
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
		if($cid == 0)
		{
			self::_blank_image($rotate, $wp); // width x height
			exit();
		}

		$file = Media_Model::get_file($cid);
		$thumb_hash = md5($cid . $rotate . $wp . $h);
		$thumb_path = self::path($thumb_hash);

		if(MEDIA_ENABLE_CACHE && file_exists($thumb_path))
		{
			header('Content-Type: ' . $file['type']);
            header('Content-Length: ' . $file['size']);
			readfile($thumb_path);
		}
		else
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

	private static function _blank_image($width, $height)
	{
		$string = 'No Photo';
		$font = CAFFEINE_MODULES_PATH . 'media/fonts/' . MEDIA_FONT;

		// Shrink font size one step until it fits
		$tmp = $width / 2; // set starting font size
		$min_w = ($width / 100) * 75; // 75% of total width
		$min_h = $height / 2;
		while(true)
		{
			$font_size = $tmp;
			$font_box = imagettfbbox($font_size, 0, $font, $string);
			$font_width = $font_box[0] + $font_box[2];
			$font_height = abs($font_box[7]);

			if($font_width < $min_w && $font_height < $min_h)
				break;

			$tmp--;
		}

		$font_x = ($width - $font_width) / 2;
		$font_y = ($height + $font_height) / 2;

		$im = imagecreatetruecolor($width, $height);
		$bg = imagecolorallocate($im, 138, 138, 138);
		$textcolor = imagecolorallocate($im, 0, 0, 0);
		imagefilledrectangle($im, 0, 0, $width, $height, $bg);

		//imagestring($im, $font, $font_x, $font_y, $string, $textcolor);
		imagettftext($im, $font_size, 0, $font_x, $font_y, $textcolor, $font, $string);

		header('content-type: image/png');
		imagepng($im);
		imagedestroy($im);
	}

}
