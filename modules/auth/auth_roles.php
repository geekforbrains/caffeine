<?php 
/**
 * =============================================================================
 * Auth_Roles
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 *
 * This class handles getting, creating and editing roles for the Auth module.
 * =============================================================================
 */
class Auth_Roles extends Database {

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
		self::query('SELECT * FROM {auth_roles} ORDER BY role ASC');
		return self::fetch_all();
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
		self::query('SELECT * FROM {auth_roles} WHERE id = %s', $id);
		return self::fetch_array();
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
		self::query('SELECT id FROM {auth_roles} WHERE role LIKE %s', $role);

		if(self::num_rows() > 0)
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
		self::query('INSERT INTO {auth_roles} (site_id, role) VALUES (%s, %s)', 
			$site_id, $role);

		if(self::affected_rows() > 0)
			return true;
		return false;
	}

}
