<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Auth_Model_Permissions
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 *
 * This class handles getting, creating and updating role permissions for the
 * Auth module.
 * =============================================================================
 */
class Auth_Model_Permissions {
	
	/**
	 * ------------------------------------------------------------------------
	 * Gets an array of available permissions defined by other classes using 
	 * the path_callbacks event. The specific key used is "auth".
	 *
	 * @see Path::callbacks event.
	 *
	 * @return
	 *		An array of all available permissions.
	 * ------------------------------------------------------------------------
	 */
	public static function get_all_avail()
	{
		$permissions = array();
		$paths = Path::paths();

		foreach($paths as $path)
			if(isset($path['auth']) && !in_array($path['auth'], $permissions)
				&& !is_bool($path['auth']))
				$permissions[] = $path['auth'];

		return $permissions;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Returns a roles permissions in a single dimensional array.
	 *
	 * @param $role_id
	 *		The role ID to get permissions for.
	 *
	 * @return
	 *		An array of permissions associated with the given role.
	 * -------------------------------------------------------------------------
	 */
	public static function get_by_role($role_id)
	{
		Database::query('SELECT permission FROM {auth_role_permissions} WHERE
			role_id = %s', $role_id);
		$perms = Database::fetch_all();

		$new_perms = array();
		foreach($perms as $perm)
			$new_perms[] = $perm['permission'];

		return $new_perms;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Updates a roles permissions.
	 *
	 * @param $role_id
	 *		The role ID to update permissions for.
	 *
	 * @param $permissions
	 *		An array of permissions to associate with the given role.
	 *
	 * @return boolean
	 * 		True for success, False otherwise.
	 * -------------------------------------------------------------------------
	 */
	public static function update($role_id, $permissions)
	{
		// First wipe all old permissions for this role
		Database::delete('auth_role_permissions', array('role_id' => $role_id));

		// Then add new permissions
		foreach($permissions as $perm)
		{
			$status = Database::insert('auth_role_permissions', array(
				'role_id' => $role_id,
				'permission' => $perm
			));

			if(!$status)
				return false;
		}

		return true;
	}

}