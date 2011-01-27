<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
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
			'admin/admin/auth' => array(
				'title' => 'Roles',
				'alias' => 'admin/admin/auth/manage'
			),
			'admin/admin/auth/manage' => array(
				'title' => 'Manage Roles',
				'callback' => array('Auth_Admin', 'manage'),
				'auth' => 'manage roles'
			),
			'admin/admin/auth/create' => array(
				'title' => 'Create Role',
				'callback' => array('Auth_Admin', 'create'),
				'auth' => 'create roles'
			),
			'admin/admin/auth/edit/%d' => array(
				'callback' => array('Auth_Admin', 'edit'),
				'visible' => false
			),
			'admin/admin/auth/delete/%d' => array(
				'callback' => array('Auth_Admin', 'delete'),
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
