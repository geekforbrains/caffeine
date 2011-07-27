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
        {
            if(isset($path['auth']) && !is_bool($path['auth']))
            {
                if(!isset($permissions[$path['module']]))
                    $permissions[$path['module']] = array();

                if(!in_array($path['auth'], $permissions[$path['module']]))
                    $permissions[$path['module']][] = $path['auth'];
            }
        }

		return $permissions;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Returns a roles permissions in a single dimensional array.
	 *
	 * @param $role_cid
	 *		The role CID to get permissions for.
	 *
	 * @return
	 *		An array of permissions associated with the given role.
	 * -------------------------------------------------------------------------
	 */
	public static function get_by_role($role_cid)
	{
		Database::query('SELECT permission FROM {auth_role_permissions} WHERE
			role_cid = %s', $role_cid);
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
	 * @param $role_cid
	 *		The role CID to update permissions for.
	 *
	 * @param $permissions
	 *		An array of permissions to associate with the given role.
	 *
	 * @return boolean
	 * 		True for success, False otherwise.
	 * -------------------------------------------------------------------------
	 */
	public static function update($role_cid, $permissions)
	{
		// First wipe all old permissions for this role
		Database::delete('auth_role_permissions', array('role_cid' => $role_cid));

		// Then add new permissions
		foreach($permissions as $perm)
		{
			$status = Database::insert('auth_role_permissions', array(
				'role_cid' => $role_cid,
				'permission' => $perm
			));

			if(!$status)
				return false;
		}

		return true;
	}

}
