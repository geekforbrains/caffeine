<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Auth_Model_Roles
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 *
 * This class handles getting, creating and editing roles for the Auth module.
 * =============================================================================
 */
class Auth_Model_Roles {

	/**
	 * -------------------------------------------------------------------------
	 * Gets all roles ordered by name.
	 *
	 * @return array
	 *		Returns an assoc array of roles.
	 * -------------------------------------------------------------------------
	 */
	public static function get_all()
	{
		Database::query('SELECT * FROM {auth_roles} ORDER BY role ASC');
		return Database::fetch_all();
	}

	/**
	 * -------------------------------------------------------------------------
	 * Gets a role by its ID.
	 *
	 * @param $id
	 *		The role ID to get.
	 *
	 * @return array
	 *		Returns an assoc array of the given roles fields.
	 * -------------------------------------------------------------------------
	 */
	public static function get_by_id($id)
	{
		Database::query('SELECT * FROM {auth_roles} WHERE id = %s', $id);
		return Database::fetch_array();
	}

	/**
	 * -------------------------------------------------------------------------
	 * Checks if the given role name already exists.
	 *
	 * @param $role
	 *		The role name to check for existence.
	 *
	 * @param boolean
	 *		True if the role exists, false otherwise.
	 * -------------------------------------------------------------------------
	 */
	public static function exists($role)
	{
		Database::query('SELECT id FROM {auth_roles} WHERE role LIKE %s', $role);

		if(Database::num_rows() > 0)
			return true;
		return false;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Creates a new role.
	 *
	 * @param $role
	 *		The name of the new role to create.
	 *
	 * @return boolean.
	 *		True on success, False otherwise.
	 * -------------------------------------------------------------------------
	 */
	public static function create($site_id, $role)
	{
		return Database::insert('auth_roles', array(
			'site_id' => $site_id,
			'role' => $role
		));
	}

	/**
	 * -------------------------------------------------------------------------
	 * Deletes a role based on its ID.
	 *
	 * @param $role_id
	 *		The role to be deleted.
	 *
	 * @return boolean
	 *		Returns true if a role with that ID was found, and was deleted.
	 *		False otherwise.
	 * -------------------------------------------------------------------------
	 */
	public static function delete($role_id)
	{
		if(self::get_by_id($role_id))
		{
			Database::delete('auth_role_permissions', array('role_id' => $role_id));
			return Database::delete('auth_roles', array('id' => $role_id));
		}

		return false;
	}

}
