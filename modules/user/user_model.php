<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * User_Model
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class User_Model extends Database {

	/**
	 * -------------------------------------------------------------------------
	 * Gets a user by its id.
	 *
	 * @param $user_id
	 *		The user ID you want.
	 *
	 * @return mixed
	 *		An associative array of data for the given user ID. If the given
	 *		user doesn't exist, boolean false is returned.
	 * -------------------------------------------------------------------------
	 */
	public static function get_by_id($user_id)
	{
		Database::query('
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
				ua.id = %s', 
			$user_id
		);

		if(Database::num_rows() > 0)
		{
			$user = Database::fetch_array();
			$user['roles'] = self::_get_roles($user_id);
			$user['permissions'] = self::_get_permissions($user_id);

			return $user;
		}

		return false;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Gets all users for the current site. Will always exclude the root user.
	 *
	 * @return array
	 *		Returns a multi-dimensional associative array of user accounts.
	 * -------------------------------------------------------------------------
	 */
	public static function get_all()
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
				ua.id != %s
				AND ua.site_id = %s
			ORDER BY 
				ua.username ASC',
			USER_ROOT_ID,
			User::current_site()
		);

		$rows = Database::fetch_all();

		foreach($rows as &$row)
		{
			$row['roles'] = self::_get_roles($row['id']);
			$row['permissions'] = self::_get_permissions($row['id']);
		}	

		return $rows;
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
		Database::query('
			SELECT
				ar.id,
				ar.role
			FROM {auth_roles} ar
				LEFT JOIN {user_roles} ur ON ur.role_id = ar.id
			WHERE
				ur.user_id = %s
		', $user_id);

		$rows = Database::fetch_all();
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
		Database::query('
			SELECT DISTINCT
				arp.permission
			FROM {auth_role_permissions} arp
				LEFT JOIN {user_roles} ur ON ur.role_id = arp.role_id
			WHERE
				ur.user_id = %s',
			$user_id
		);

		$rows = Database::fetch_all();
		$perms = array();

		foreach($rows as $row)
			$perms[] = $row['permission'];

		return $perms;
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function check_login($username, $pass, $site_id)
	{
		Database::query('
			SELECT id
			FROM {user_accounts}
			WHERE
				(username = %s AND pass = MD5(%s) AND site_id = %s) OR
				(username = %s AND pass = MD5(%s) AND site_id = %s)
			',
			$username, 
			$pass, 
			$site_id, 
			USER_ROOT_USERNAME, 
			$pass,
			USER_ROOT_SITE_ID
		);

		if(Database::num_rows() > 0)
			return Database::fetch_single('id');
	
		return false;
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function update_roles($user_id, $roles)
	{
		// Clear old rows
		self::query('DELETE FROM {user_roles} WHERE user_id = %s', $user_id);

		// Add new roles
		foreach($roles as $role)
			self::query('INSERT INTO {user_roles} (user_id, role_id) VALUES
				(%s, %s)', $user_id, $role);
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function username_exists($username)
	{
		self::query('
			SELECT id FROM {user_accounts} 
			WHERE username LIKE %s AND site_id = %s', 
			$username,
			User::current_site()
		);

		if(self::num_rows() > 0)
			return true;
		return false;
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function add_user($username, $pass, $email, $site_id)
	{
		Database::insert('user_accounts', array(
			'site_id' => $site_id,
			'username' => $username,
			'pass' => md5($pass),
			'email' => $email
		));

		return true;
	}

	public static function update_user($id, $username, $email, $is_root)
	{
		Database::update('user_accounts',
			array(
				'username' => $username,
				'email' => $email,
				'is_root' => $is_root
			),
			array('id' => $id)
		);
	}

	public static function update_pass($id, $pass)
	{
		Database::update('user_accounts',
			array('pass' => md5($pass)),
			array('id' => $id)
		);
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function get_site_id($site) 
	{
		self::query('SELECT id FROM {user_sites} WHERE site = %s', $site);
		if(self::num_rows() > 0)
			return self::fetch_single('id');

		return USER_ROOT_SITE_ID;
	}

	public static function site_exists($site)
	{
		Database::query('SELECT id FROM {user_sites} WHERE site LIKE %s', $site);
		
		if(Database::num_rows() > 0)
			return true;
		return false;
	}

	public static function create_site($site)
	{
		Database::insert('user_sites', array('site' => $site));

		if(Database::affected_rows() > 0)
			return true;
		return false;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Used to create an initial root user in the system. If the setting is
	 * enabled, this user will be re-created at each page refresh. It should be
	 * disabled on production sites.
	 *  
	 * TODO Move to model
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

}
