<?php
/**
 * ============================================================================
 * User_Events
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * ============================================================================
 */
final class User_Events {

	public static function caffeine_init()
	{
		if(USER_CREATE_ROOT)
			User::create_root();

		User::load();
	}

	public static function view_block_paths() 
	{
		return array(
			'User' => CAFFEINE_MODULES_PATH . 'user/blocks/',
			'User_Admin' => CAFFEINE_MODULES_PATH . 'user/blocks/admin/'
		);
	}

	public static function path_callbacks()
	{
		$paths = array(
			'admin/login' => array(
				'title' => 'Login',
				'callback' => array('User_Admin', 'login'),
				'auth' => true,
				'visible' => false
			),
			'admin/user' => array(
				'title' => 'Users',
				'callback' => array('User_Admin', 'manage'),
				'auth' => 'manage users'
			),
			'admin/user/manage' => array(
				'title' => 'Manage Users',
				'callback' => array('User_Admin', 'manage'),
				'auth' => 'manage users'
			),
			'admin/user/create' => array(
				'title' => 'Create User',
				'callback' => array('User_Admin', 'create'),
				'auth' => 'create users'
			),
			'admin/user/edit/%d' => array(
				'callback' => array('User_Admin', 'edit'),
				'auth' => 'edit users',
				'visible' => false
			)
		);

		if($_SESSION['user'] > 0)
		{
			$paths['admin/logout'] = array(
				'title' => 'Logout',
				'callback' => array('User_Admin', 'logout'),
				'auth' => true
			);
		}

		return $paths;
	}

	public static function database_install()
	{
		return array(
			'user_sites' => array(
				'fields' => array(
					'id' => array(
						'type' => 'auto increment',
						'unsigned' => true,
						'not null' => true
					),
					'site' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					)
				),

				'primary key' => array('id')
			),

			'user_accounts' => array(
				'fields' => array(
					'id' => array(
						'type' => 'auto increment',
						'unsigned' => true,
						'not null' => true
					),
					'site_id' => array(
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
					'username' => array('username'),
					'email' => array('email')
				),

				'primary key' => array('id')
			),

			'user_roles' => array(
				'fields' => array(
					'user_id' => array(
						'type' => 'int',
						'size' => 'normal',
						'unsigned' => true,
						'not null' => true
					),
					'role_id' => array(
						'type' => 'int',
						'size' => 'normal',
						'unsigned' => true,
						'not null' => true
					)
				),

				'indexes' => array(
					'user_id' => array('user_id'),
					'role_id' => array('role_id')
				)
			)
		);
	}

}
