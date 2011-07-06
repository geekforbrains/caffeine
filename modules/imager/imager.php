<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Imager
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 *
 * The Imager module provides a "Caffeine" based interface for the popular
 * PHPThumb classes. This class does NOT provide the actual mail functionality,
 * but rather static methods that can be used throughout the application.
 *
 * @credit https://github.com/masterexploder/PHPThumb
 * =============================================================================
 */
class Imager {

	private static $_thumb;

	public static function open($path)
	{
		require_once(CAFFEINE_MODULES_PATH . 'imager/phpthumb/ThumbLib.inc.php');
		self::$_thumb = PhpThumbFactory::create($path, array('resizeUp' => true));
	}

	public static function resize($width, $height, $adaptive = false) 
	{
		if($adaptive)
			self::$_thumb->adaptiveResize($width, $height);
		else
			self::$_thumb->resize($width, $height);
	}

	public static function percent($percent) {
		self::$_thumb->resizePercent($percent);
	}

	public static function crop($width, $height = null, $x = null, $y = null)
	{
		// Check if we're cropping from center or not
		if(!is_null($x) && !is_null($y))
			self::$_thumb->crop($x, $y, $width, $height);
		else
			self::$_thumb->cropFromCenter($width, $height);
	}

	public static function rotate($degrees) {
		self::$_thumb->rotateImageNDegrees($degrees);
	}

	public static function show() {
		self::$_thumb->show();
	}

	public static function save($path, $format = null) {
		self::$_thumb->save($path, $format);
	}

}
