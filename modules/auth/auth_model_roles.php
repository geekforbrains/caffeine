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
		Database::query('
			SELECT 
				ar.*,
				c.created,
				c.updated
			FROM {auth_roles} ar
				JOIN {content} c ON c.id = ar.cid
			WHERE
				c.site_cid = %s
			ORDER BY 
				ar.role ASC
			',
			User::current_site()
		);

		return Database::fetch_all();
	}

	/**
	 * -------------------------------------------------------------------------
	 * Gets a role by its CID.
	 *
	 * @param $cid
	 *		The role CID to get.
	 *
	 * @return array
	 *		Returns an assoc array of the given roles fields.
	 * -------------------------------------------------------------------------
	 */
	public static function get_by_cid($cid)
	{
		Database::query('
			SELECT 
				ar.*,
				c.created,
				c.updated
			FROM {auth_roles} ar
				JOIN {content} c ON c.id = ar.cid
			WHERE ar.cid = %s
				AND c.site_cid = %s
			', 
			$cid,
			User::current_site()
		);

		if(Database::num_rows() > 0)
			return Database::fetch_array();
		return false;
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
		Database::query('
			SELECT 
				ar.cid 
			FROM {auth_roles} ar
				JOIN {content} c ON c.id = ar.cid
			WHERE ar.role LIKE %s
				AND c.site_cid = %s
			', 
			$role,
			User::current_site()
		);

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
	public static function create($role)
	{
		$cid = Content::create(AUTH_TYPE_ROLE);

		return Database::insert('auth_roles', array(
			'cid' => $cid,
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
	public static function delete($cid)
	{
		if(self::get_by_cid($cid))
		{
			Content::delete($cid);
			Database::delete('auth_role_permissions', array('role_cid' => $cid));
			return Database::delete('auth_roles', array('cid' => $cid));
		}

		return false;
	}

}
