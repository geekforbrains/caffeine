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
				'title' => 'Edit Role',
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
					'cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'role' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					)
				),

				'primary key' => array('cid')
			),

			'auth_role_permissions' => array(
				'fields' => array(
					'role_cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'permission' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					)
				),

				'indexes' => array(
					'role_cid' => array('role_cid'),
					'permission' => array('permission')
				)
			)
		);	
	}

}
