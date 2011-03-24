<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * User_Model
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class User_Model {

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
	public static function get_by_cid($cid)
	{
		Database::query('
			SELECT
				ua.cid,
				ua.site_cid,
				ua.username,
				ua.email,
				ua.is_admin,
				ua.is_root,
				us.site
			FROM {user_accounts} ua
				LEFT JOIN {user_sites} us ON us.cid = ua.site_cid
			WHERE
				ua.cid = %s
			', 
			$cid
		);

		if(Database::num_rows() > 0)
		{
			$user = Database::fetch_array();
			$user['roles'] = self::_get_roles($cid);
			$user['permissions'] = self::_get_permissions($cid);

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
		Database::query('
			SELECT
				ua.cid,
				ua.site_cid,
				ua.username,
				ua.email,
				ua.is_admin,
				ua.is_root,
				us.site
			FROM {user_accounts} ua
				LEFT JOIN {user_sites} us ON us.cid = ua.site_cid
			WHERE 
				ua.is_root = 0
				AND ua.site_cid = %s
			ORDER BY 
				ua.username ASC
			',
			User::current_site()
		);

		$rows = Database::fetch_all();

		foreach($rows as &$row)
		{
			$row['roles'] = self::_get_roles($row['cid']);
			$row['permissions'] = self::_get_permissions($row['cid']);
		}	

		return $rows;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Gets the roles associated with the given user CID.
	 *
	 * @param $cid
	 *		The user CID you want roles for.
	 *
	 * @return array
	 *		Returns an array of roles.
	 * -------------------------------------------------------------------------
	 */
	private static function _get_roles($cid)
	{
		Database::query('
			SELECT
				ar.cid,
				ar.role
			FROM {auth_roles} ar
				LEFT JOIN {user_roles} ur ON ur.role_cid = ar.cid
			WHERE
				ur.user_cid = %s
			', 
			$cid
		);

		$rows = Database::fetch_all();
		$roles = array();

		foreach($rows as $row)
			$roles[$row['cid']] = $row['role'];

		return $roles;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Gets permissions for the given user CID, based on their roles. A user will
	 * inherit all permissions of the role he is apart of. Duplicate permissions
	 * are ignored.
	 *
	 * @param $cid
	 *		The user CID you want permissions for.
	 *
	 * @return array
	 *		An array of permissions.
	 * -------------------------------------------------------------------------
	 */
	private static function _get_permissions($cid)
	{
		Database::query('
			SELECT DISTINCT
				arp.permission
			FROM {auth_role_permissions} arp
				LEFT JOIN {user_roles} ur ON ur.role_cid = arp.role_cid
			WHERE
				ur.user_cid = %s
			',
			$cid	
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
	public static function check_login($username, $pass, $site_cid)
	{
		$root = self::get_root();

		Database::query('
			SELECT 
				cid
			FROM {user_accounts}
			WHERE
				(username = %s AND pass = MD5(%s) AND site_cid = %s) OR
				(username = %s AND pass = MD5(%s) AND site_cid = %s)
			',
			$username, 
			$pass, 
			$site_cid, 
			USER_ROOT_USERNAME, 
			$pass,
			$root['site_cid']
		);

		if(Database::num_rows() > 0)
			return Database::fetch_single('cid');
	
		return false;
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function update_roles($cid, $roles)
	{
		// Clear old rows
		Database::query('DELETE FROM {user_roles} WHERE user_cid = %s', $cid);

		// Add new roles
		foreach($roles as $role)
			Database::query('INSERT INTO {user_roles} (user_cid, role_cid) VALUES
				(%s, %s)', $cid, $role);
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function username_exists($username)
	{
		Database::query('
			SELECT cid FROM {user_accounts} 
			WHERE username LIKE %s AND site_cid = %s', 
			$username,
			User::current_site()
		);

		if(Database::num_rows() > 0)
			return true;
		return false;
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function add_user($username, $pass, $email, $site_cid)
	{
		// Failsafe for never creating users with same username as root
		if($username == USER_ROOT_USERNAME)
			return false;

		$cid = Content::create(USER_TYPE);

		Database::insert('user_accounts', array(
			'cid' => $cid,
			'site_cid' => $site_cid,
			'username' => $username,
			'pass' => md5($pass),
			'email' => $email
		));

		return true;
	}

	public static function update_user($cid, $username, $email, $is_admin)
	{
		Content::update($cid);

		Database::update('user_accounts',
			array(
				'username' => $username,
				'email' => $email,
				'is_admin' => $is_admin
			),
			array('cid' => $cid)
		);
	}

	public static function update_pass($cid, $pass)
	{
		Content::update($cid);

		Database::update('user_accounts',
			array('pass' => md5($pass)),
			array('cid' => $cid)
		);
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function get_site_cid($site) 
	{
		Database::query('SELECT cid FROM {user_sites} WHERE site = %s', $site);
		if(Database::num_rows() > 0)
			return Database::fetch_single('cid');

		$root = self::get_root();
		return $root['site_cid'];
	}

	public static function site_exists($site)
	{
		Database::query('SELECT cid FROM {user_sites} WHERE site LIKE %s', $site);
		
		if(Database::num_rows() > 0)
			return true;
		return false;
	}

	public static function create_site($site)
	{
		$cid = Content::create(USER_TYPE_SITE);

		Database::insert('user_sites', array(
			'cid' => $cid,
			'site' => $site
		));

		if(Database::affected_rows() > 0)
			return true;
		return false;
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
		$root = self::get_root();
		
		// If a root user hasn't been created yet
		if($root['cid'] == 0)
		{
			Debug::log('User', 'Creating initial root user');

			// Create site
			$site_cid = Content::create(USER_TYPE_SITE);
			Database::insert('user_sites', array(
				'cid' => $site_cid,
				'site' => USER_ROOT_SITE
			));

			// Create user
			$root_cid = Content::create(USER_TYPE);
			Database::insert('user_accounts', array(
				'cid' => $root_cid,
				'site_cid' => $site_cid,
				'username' => USER_ROOT_USERNAME,
				'pass' => md5(USER_ROOT_PASS),
				'email' => USER_ROOT_EMAIL,
				'is_root' => 1
			));
		}

		// If root already exists, only update password
		else
		{
			Database::update('user_accounts',
				array('pass' => md5(USER_ROOT_PASS)),
				array('cid' => $root['cid'])
			);
		}
	}

	public static function get_root()
	{
		Database::query('
			SELECT 
				ua.cid,
				ua.site_cid,
				ua.username,
				ua.email
			FROM 
				{user_accounts} ua
			WHERE  
				ua.username = %s
				AND ua.is_root = %s
			',
			USER_ROOT_USERNAME,
			1
		);

		if(Database::num_rows() > 0)
			return Database::fetch_array();

		// Blank incase root hasn't been created yet
		return array(
			'cid' => 0,
			'site_cid' => 0,
			'username' => USER_ROOT_USERNAME,
			'email' => USER_ROOT_EMAIL
		);
	}

}
