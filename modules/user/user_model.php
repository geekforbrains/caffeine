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
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function check_login($username, $pass, $site)
	{
		self::query('
			SELECT 
				ua.id
			FROM {user_accounts} ua
				LEFT JOIN {user_sites} us ON us.id = ua.site_id
			WHERE
				ua.username = %s
				AND ua.pass = MD5(%s)
				AND (us.site = %s OR us.site = %s)
		', $username, $pass, $site, USER_ROOT_SITE);

		if(self::num_rows() > 0)
			return self::fetch_single('id');
	
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
		self::query('SELECT id FROM {user_accounts} WHERE username LIKE %s', 
			$username);

		if(self::num_rows() > 0)
			return true;
		return false;
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function add_user($username, $pass, $email, $site)
	{
		$site_id = self::get_site_id($site);

		if($site_id)
		{
			self::query('
				INSERT INTO {user_accounts} (site_id, username, pass, email)
				VALUES (%s, %s, MD5(%s), %s)', 
				$site_id, $username, $pass, $email
			);
			return true;
		}
		else
		{
			Message::set('error', 'The site "' .$site. '" doesn\'t exist.');
			return false;
		}
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

		return false;
	}

}
