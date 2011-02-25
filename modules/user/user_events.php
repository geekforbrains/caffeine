<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * ============================================================================
 * User_Events
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * ============================================================================
 */
final class User_Events {

	/**
	 * -------------------------------------------------------------------------
	 * Implements the Caffeine::init event.
	 * -------------------------------------------------------------------------
	 */
	public static function caffeine_init() 
	{
		User_Model::create_root();

		if(USER_AUTOCREATE_SITES)
		{
			$site = Caffeine::site();	
			if(!is_null($site) && !User_Model::site_exists($site))
				User_Model::create_site($site);
		}

		User::load();
	}

	/**
	 * -------------------------------------------------------------------------
	 * Implements the Path::callbacks event.
	 * -------------------------------------------------------------------------
	 */
	public static function path_callbacks()
	{
		$paths = array(
			'admin/login' => array(
				'title' => 'Login',
				'callback' => array('User_Admin', 'login'),
				'auth' => true,
				'visible' => false
			),
			'admin/admin/user' => array(
				'title' => 'Users',
				'alias' => 'admin/admin/user/manage'
			),
			'admin/admin/user/manage' => array(
				'title' => 'Manage Users',
				'callback' => array('User_Admin', 'manage'),
				'auth' => 'manage users'
			),
			'admin/admin/user/create' => array(
				'title' => 'Create User',
				'callback' => array('User_Admin', 'create'),
				'auth' => 'create users'
			),
			'admin/admin/user/edit/%d' => array(
				'title' => 'Edit User',
				'callback' => array('User_Admin', 'edit'),
				'auth' => 'edit users',
				'visible' => false
			)
		);

		if(isset($_SESSION['user']) && $_SESSION['user'] > 0)
		{
			$paths['admin/logout'] = array(
				'title' => 'Logout',
				'callback' => array('User_Admin', 'logout'),
				'auth' => true,
				'visible' => false
			);
		}

		return $paths;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Implements the Caffeine::init event.
	 * -------------------------------------------------------------------------
	 */
	public static function database_install()
	{
		return array(
			'user_sites' => array(
				'fields' => array(
					'cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'site' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					)
				),

				'primary key' => array('cid')
			),

			'user_accounts' => array(
				'fields' => array(
					'cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'site_cid' => array(
						'type' => 'int',
						'size' => 'normal',
						'unsigned' => true,
						'not null' => true
					),
					'username' => array(
						'type' => 'varchar',
						'length' => 50,
						'not null' => true
					),
					'pass' => array(
						'type' => 'varchar',
						'length' => 32,
						'not null' => true
					),
					'email' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					),
					'is_root' => array(
						'type' => 'int',
						'size' => 'tiny',
						'not null' => true
					)
				),

				'indexes' => array(
					'site_cid' => array('site_cid'),
					'username' => array('username'),
					'email' => array('email')
				),

				'primary key' => array('cid')
			),

			'user_roles' => array(
				'fields' => array(
					'user_cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'role_cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					)
				),

				'indexes' => array(
					'user_cid' => array('user_cid'),
					'role_cid' => array('role_cid')
				)
			)
		);
	}

}
