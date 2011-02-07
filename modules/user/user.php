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

	// Stores metadata about the current user, whether anonymous or logged in
	private static $_user = array();

	/**
	 * -------------------------------------------------------------------------
	 * Gets all of a users information once, and makes it available to the
	 * entire application. This makes for a more central location to get
	 * trusted user info, as well as cut down on database requests.
	 *
	 * This method is called at the beginning of every Caffeine bootstrap.
	 * -------------------------------------------------------------------------
	 */
	public static function load()
	{
		if(!isset($_SESSION['user']) || $_SESSION['user'] <= 0) 
		{
			$_SESSION['user'] = 0;
			self::$_user = array(
				'id' => 0,
				'site_id' => 0,
				'username' => null,
				'email' => null,
				'is_root' => 0,
				'site' => null,
				'roles' => array(),
				'permissions' => array()
			);
		}
		else
			self::$_user = self::get_by_id($_SESSION['user']);

		Debug::log('User', 'Current user ID is: %s', self::$_user['id']);
	}

	/**
	 * -------------------------------------------------------------------------
	 * Used to create an initial root user in the system. If the setting is
	 * enabled, this user will be re-created at each page refresh. It should be
	 * disabled on production sites.
	 * -------------------------------------------------------------------------
	 */
	public static function create_root()
	{
		Debug::log('User', 'Creating initial root user');

		// Create site
		Database::query('DELETE FROM {user_sites} WHERE id = %s', USER_ROOT_ID);
		Database::query('INSERT INTO {user_sites} (id, site) VALUES (%s, %s)',
			USER_ROOT_SITE_ID, USER_ROOT_SITE);

		// Create user
		Database::query('DELETE FROM {user_accounts} WHERE id = %s', USER_ROOT_ID);

		Database::query('
			INSERT INTO {user_accounts} (id, site_id, username, pass, email, is_root) VALUES 
				(%s, %s, %s, MD5(%s), %s, %s)',
			USER_ROOT_ID,
			USER_ROOT_SITE_ID,
			USER_ROOT_USERNAME,
			USER_ROOT_PASS,
			USER_ROOT_EMAIL,
			1
		);
	}

	/**
	 * -------------------------------------------------------------------------
	 * Returns an array of metadata about the current user. This information
	 * is loaded by the User::load method.
	 *
	 * @return array
	 *		An associative array of current user metadata.
	 * -------------------------------------------------------------------------
	 */
	public static function get_current() {
		return self::$_user;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Returns an array of metadata for the given user ID
	 *
	 * @param $user_id
	 *		The user ID you want the metadata for.
	 *
	 * @return mixed
	 *		An associative array of metadata for the given user ID. If the given
	 *		user doesn't exist, boolean false is returned.
	 * -------------------------------------------------------------------------
	 */
	public static function get_by_id($user_id)
	{
		self::query('
			SELECT
				ua.id,
				ua.site_id,
				ua.username,
				ua.email,
				ua.is_root,
				us.site
			FROM {user_accounts} ua
				LEFT JOIN {user_sites} us ON us.id = ua.site_id
			WHERE
				ua.id = %s
		', $user_id);

		if(self::num_rows() > 0)
		{
			$user = self::fetch_array();
			$user['roles'] = self::_get_roles($user_id);
			$user['permissions'] = self::_get_permissions($user_id);

			return $user;
		}

		return false;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Gets all users for either the current site or the entire system.
	 *
	 * @param $entire_system
	 *		A boolean value determining whether to get users of the current 
	 *		site or all users of the entire system. Defaults to false.
	 *
	 * @return array
	 *		Returns a multi-dimensional associative array of user accounts.
	 * -------------------------------------------------------------------------
	 */
	public static function get_all($entire_site = false)
	{
		self::query('SELECT id, username, email FROM {user_accounts}
			ORDER BY username ASC');

		$rows = self::fetch_all();

		foreach($rows as &$row)
		{
			$row['roles'] = self::_get_roles($row['id']);
			$row['permissions'] = self::_get_permissions($row['id']);
		}	

		return $rows;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Gets the site ID of the given site, if it exists.
	 *
	 * @param $site
	 *		The site name to get an ID for. If not set, defaults to the current
	 *		site.
	 *
	 * @return mixed
	 *		Returns the site ID if found, false otherwise.
	 * -------------------------------------------------------------------------
	 */
	public static function site_id($site = null)
	{
		if(is_null($site))
			$site = Caffeine::site();

		self::query('SELECT id FROM {user_sites} WHERE site = %s', $site);
		
		if(self::num_rows() > 0)
			return self::fetch_single('id');

		return false;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Gets the roles associated with the given user ID.
	 *
	 * @param $user_id
	 *		The user ID you want roles for.
	 *
	 * @return array
	 *		Returns an array of roles.
	 * -------------------------------------------------------------------------
	 */
	private static function _get_roles($user_id)
	{
		self::query('
			SELECT
				ar.id,
				ar.role
			FROM {auth_roles} ar
				LEFT JOIN {user_roles} ur ON ur.role_id = ar.id
			WHERE
				ur.user_id = %s
		', $user_id);

		$rows = self::fetch_all();
		$roles = array();

		foreach($rows as $row)
			$roles[$row['id']] = $row['role'];

		return $roles;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Gets permissions for the given user ID, based on their roles. A user will
	 * inherit all permissions of the role he is apart of. Duplicate permissions
	 * are ignored.
	 *
	 * @param $user_id
	 *		The user ID you want permissions for.
	 *
	 * @return array
	 *		An array of permissions.
	 * -------------------------------------------------------------------------
	 */
	private static function _get_permissions($user_id)
	{
		self::query('
			SELECT DISTINCT
				arp.permission
			FROM {auth_role_permissions} arp
				LEFT JOIN {user_roles} ur ON ur.role_id = arp.role_id
			WHERE
				ur.user_id = %s',
			$user_id
		);

		$rows = self::fetch_all();
		$perms = array();

		foreach($rows as $row)
			$perms[] = $row['permission'];

		return $perms;
	}

}
