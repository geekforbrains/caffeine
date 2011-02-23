<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * User
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 *
 * The User class is used to get information about the current user, all users
 * or a specific user based on their ID.
 * =============================================================================
 */
class User extends Database {

	// Stores data about the current user, defaults to blank
	private static $_user = array(
		'id' => 0,
		'is_root' => 0,
		'site_id' => 0,
		'site' => null,
		'username' => null,
		'email' => null,
		'site_path' => null,
		'files_path' => null,
		'roles' => array(),
		'permissions' => array()
	);

	/**
	 * -------------------------------------------------------------------------
	 * Used for getting a single value from the current user array. This is
	 * cleaner than calling User::current() to get the entire array, and then
	 * using the returned array to get a value.
	 *
	 * @param $key
	 *		The key within the user array you want a value for.
	 *
	 * @param mixed
	 *		If the key exists, it will return its value. Otherwise, boolean
	 *		false is returned.
	 * -------------------------------------------------------------------------
	 *
	 */
	public static function get($key)
	{
		if(isset(self::$_user[$key]))
			return self::$_user[$key];
		return false;
	}
	
	/**
	 * -------------------------------------------------------------------------
	 * Returns the current user array.
	 * -------------------------------------------------------------------------
	 */
	public static function current() {
		return self::$_user;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Returns the current site ID. This is the current site as Caffeine sees
	 * it. It may not be the same as the "site_id" in the user array.
	 *
	 * This is the most reliable way to get the site ID of the current site
	 * being viewed.
	 * -------------------------------------------------------------------------
	 */
	public static function current_site() {
		return User_Model::get_site_id(Caffeine::site());
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function load()
	{
		if(isset($_SESSION['user']) && $_SESSION['user'] > 0)
		{
			$_SESSION['timeout'] = time();

			if($user = User_Model::get_by_id($_SESSION['user']))
				self::$_user = $user;
		}

		self::$_user['site_path'] = Caffeine::site_path();
		self::$_user['files_path'] = Caffeine::files_path();

		Debug::log('User', 'Current user ID is: %s', self::$_user['id']);
		Debug::log('User', print_r(self::$_user, true));
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	private static function _timed_out()
	{
		if(!isset($_SESSION['timeout']) && $_SESSION['timeout'] + USER_TIMEOUT * 60 < time())
			return true;
		return false;
	}

}
