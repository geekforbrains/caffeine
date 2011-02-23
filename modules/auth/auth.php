<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Auth
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 *
 * The Auth module makes use of the Path and User modules to determine if a user
 * has access to the current URL on the current site. This is determined by 
 * checking the current paths auth permissions against the permissions of the 
 * sites role(s) the user is a part of.
 * =============================================================================
 */
class Auth {

	/**
	 * -------------------------------------------------------------------------
	 * Checks if the current user has access to the given path on the current
	 * site. 
	 *
	 * @param $current_path
	 *		The current URL path. This is typically obtained using the
	 *		Router::current_path method.
	 *
	 * @param $path_data
	 *		The path data associated with $current_path. This is provided by
	 *		the Path::get_path method.
	 *
	 * @param $current_site
	 *		The current site. This is typically obtained using the
	 *		Caffeine::site method.
	 *
	 * @return boolean
	 *		Returns true if the user has access, false otherwise.
	 * -------------------------------------------------------------------------
	 */
	public static function check_access($current_path, $path_data, $current_site = null)
	{
		if(is_null($current_site))
		{
			$site = Caffeine::site();
			$current_site = ($site) ? $site : USER_ROOT_SITE;
		}

		Debug::log('Auth', 'Checking access to path "%s" on site "%s"', 
			$current_path, $current_site);

		if($path_data['auth'] === true)
		{
			Debug::log('Auth', 'WARNING: Path access is set to true. Allowing
				access to anybody.');

			return true;
		}

		$user = User::current();

		// If for some reason the user didnt exist, reject access and
		// log attempt as a possible break-in warning
		if(isset($_SESSION['user']) && $_SESSION['user'] > 0 && !$user)
		{
			Debug::log('Auth', 'WARNING: Possible break-in attempt.
				User ID "%s" doesn\'t exist, yet tried to authenticate.',
				$_SESSION['user']);

			return false;
		}

		// Check for super root, which is the "root" user of the "root site"
		if($user['is_root'] && $user['id'] == USER_ROOT_ID && $user['site_id'] == USER_ROOT_SITE_ID)
		{
			Debug::log('Auth', 'WARNING: User is super root. Granting access on all.');
			return true;
		}

		// Check if user is root of the current site path, if they are, allow access to 
		// everything under the roots site
		if($user['is_root'] && $user['site'] == $current_site)
		{
			Debug::log('Auth', 'User is root of the current site.
				Granting all access on this site only.');

			return true;
		}

		// Check if user has permissions for this path specifically
		if(in_array($path_data['auth'], $user['permissions']))
		{
			Debug::log('Auth', 'User has permission to access: %s',
				$path_data['auth']);

			return true;
		}

		Debug::log('Auth', 'All auth attempts failed. Rejecting access.');
		return false;
	}

}
