<?php
/**
 * =============================================================================
 * Auth_Events
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
final class Auth_Events {

	/**
	 * ------------------------------------------------------------------------
	 * Implements the View::block_paths event.
	 * ------------------------------------------------------------------------
	 */
	public static function view_block_paths() {
		return array('Auth_Admin' => CAFFEINE_MODULES_PATH . 
			'auth/blocks/admin/');
	}

	/**
	 * ------------------------------------------------------------------------
	 * Implements the Path::callbacks event.
	 * ------------------------------------------------------------------------
	 */
	public static function path_callbacks()
	{
		return array(
			'admin/auth' => array(
				'title' => 'Roles',
				'callback' => array('Auth_Admin', 'manage'),
				'auth' => 'manage roles'
			),
			'admin/auth/manage' => array(
				'title' => 'Manage Roles',
				'callback' => array('Auth_Admin', 'manage'),
				'auth' => 'manage roles'
			),
			'admin/auth/create' => array(
				'title' => 'Create Role',
				'callback' => array('Auth_Admin', 'create'),
				'auth' => 'create roles'
			),
			'admin/auth/edit/%d' => array(
				'callback' => array('Auth_Admin', 'edit'),
				'visible' => false
			)
		);	
	}

	/**
	 * ------------------------------------------------------------------------
	 * Implements the Database::install event.
	 * ------------------------------------------------------------------------
	 */
	public static function database_install()
	{
		return array(
			'auth_roles' => array(
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
					'role' => array(
						'type' => 'varchar',
						'length' => 50,
						'not null' => true
					)
				),

				'primary key' => array('id')
			),

			'auth_role_permissions' => array(
				'fields' => array(
					'role_id' => array(
						'type' => 'int',
						'size' => 'normal',
						'unsigned' => true,
						'not null' => true
					),
					'permission' => array(
						'type' => 'varchar',
						'length' => 50,
						'not null' => true
					)
				),

				'indexes' => array(
					'role_id' => array('role_id'),
					'permission' => array('permission')
				)
			)
		);	
	}

}
