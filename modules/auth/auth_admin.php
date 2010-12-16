<?php
/**
 * =============================================================================
 * Auth_Admin
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 *
 * Used to display admin pages and manage admin actions such as creating new
 * roles and updating role permissions.
 * =============================================================================
 */
class Auth_Admin {

	/**
	 * -------------------------------------------------------------------------
	 * Displays a list of current roles. Roles can be clicked to edit its
	 * permissions.
	 * -------------------------------------------------------------------------
	 */
	public static function manage()
	{
		View::load('Auth_Admin', 'auth_admin_manage', 
			array('roles' => Auth_Roles::get_all()));
	}

	/**
	 * -------------------------------------------------------------------------
	 * Used for creating new roles.
	 * -------------------------------------------------------------------------
	 */
	public static function create()
	{
        if($_POST)
        {
            if(!Auth_Roles::exists($_POST['role']))
            {
              	if(Auth_Roles::create(User::site_id(), $_POST['role']))
				{
                	Message::store('success', 'Role created successfully.');
                	Router::redirect('admin/auth/manage');
				}
				else
					Message::set('error', 'Error creating role. Please try again.');
            }
            else
                Message::set('error', 'A role with that name already exists.');
        }
        
		View::load('Auth_Admin', 'auth_admin_create',
			array('permissions' => array()));
	}

	/**
	 * -------------------------------------------------------------------------
	 * Used for editing a current roles permissions.
	 * -------------------------------------------------------------------------
	 */
	public static function edit($id)
	{
		if($_POST)
		{
			if(!isset($_POST['perms']))
				$_POST['perms'] = array();

			Auth_Permissions::update(
				$_POST['role_id'],
				$_POST['perms']
			);

			Message::set('success', 'Role permissions updated successfully.');
		}

		View::load('Auth_Admin', 'auth_admin_edit',
			array(
				'role' => Auth_Roles::get_by_id($id),
				'role_perms' => Auth_Permissions::get_by_role($id),
				'avail_perms' => Auth_Permissions::get_all_avail()
			)
		);
	}

	/**
	 * -------------------------------------------------------------------------
	 * Deleted a role based on its ID.
	 *
	 * @param $role_id
	 *		The role to be deleted.
	 * -------------------------------------------------------------------------
	 */
	public static function delete($role_id)
	{
		if(Auth_Roles::delete($role_id))
			Message::store('success', 'Role deleted successfully.');
		else
			Message::store('error', 'Error deleting role. Please try again.');

		Router::redirect('admin/auth/manage');
	}

}
