<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Mailer
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 *
 * The Mailer module provides a "Caffeine" based interface for the popular
 * phpMailer class. This class does NOT provide the actual mail functionality,
 * but rather static methods that can be used throughout the application.
 * =============================================================================
 */
class Mailer {
	
	// TODO
	private static $_mail = null;

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function init()
	{
		self::$_mail = null;
		self::$_mail = new PHPMailer();
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function error() {
		return self::$_error;
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function validate_address($address)
	{
		return call_user_func(array('PHPMailer', 'ValidateAddress'), 
			$address);
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function to($address, $name = '') {
		self::$_mail->AddAddress($address, $name);
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function reply_to($address, $name = '') {
		self::$_mail->AddReplyTo($address, $name);
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function cc($address, $name = '') {
		self::$_mail->AddCC($address, $name);
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function bcc($address, $name = '') {
		self::$_mail->AddBCC($address, $name);
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function from($address, $name) 
	{
		self::$_mail->SetFrom($address, $name);
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function subject($subject) {
		self::$_mail->Subject = $subject;
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function body($body, $is_html = false) 
	{
		self::$_mail->IsHTML($is_html);
		self::$_mail->Body = $body;
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function alt_body($body) {
		self::$_mail->AltBody = $body;	
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function attach($file, $name, $type, $encoding = 'base64') {
		self::$_mail->AddAttachment($file, $name, $encoding, $type);
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function embed($path, $cid, $type, $encoding = 'base64')
	{
		self::$_mail->AddEmbeddedImage(
			$path, $cid, '', $encoding, $type
		);
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function send() 
	{
		if(!$status = self::$_mail->Send())
			self::$_error = self::$_mail->ErrorInfo;

		self::init();
		return $status;
	}

}

// If this is the first time the file is loaded,
// instantiate new phpMailer
require_once(CAFFEINE_MODULES_PATH . 'mailer/phpmailer.php');
Mailer::init();
