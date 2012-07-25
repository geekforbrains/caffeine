<?php

class User_Admin_RoleController extends Controller {

    /**
     * Displays a table for managing users.
     */
    public static function manage()
    {
        return array(
            'roles' => User::role()->orderBy('name')->all()
        );
    }

    /**
     * Displays a form for creating new roles.
     */
    public static function create()
    {
        if(Input::post('create_role'))
        {
            Validate::check('name', array('required'));

            if(Validate::passed())
            {
                $post = Input::clean($_POST);

                if(!User::role()->nameInUse($post['name']))
                {
                    $roleId = User::role()->insert(array(
                        'name' => $post['name']
                    ));

                    if($roleId)
                    {
                        Message::ok('Role created successfully.');
                        Url::redirect('admin/role/edit/' . $roleId);
                    }
                    else
                        Message::error('Error creating role.');
                }
                else
                    Message::error('A role with that name is already in use.');
            }
        }
    }

    /**
     * Displays a form for updating a role name and a table of available permissions that
     * can be checked to add to the role.
     */
    public static function edit($id)
    {
        if(!$role = User::role()->find($id))
            return 404;

        if(Input::post('update_role'))
        {
            Validate::check('name', array('required'));

            if(Validate::passed())
            {
                $post = Input::clean($_POST);

                if($post['name'] == $role->name || !User::role()->nameInUse($post['name']))
                {
                    $status = User::role()->where('id', '=', $id)->update(array(
                        'name' => $post['name']
                    ));
                    
                    if($status || $status == 0)
                    {
                        Message::ok('Role updated successfully.');
                        Url::redirect('admin/role/manage');
                    }
                    else
                        Message::error('Error updating role, please try again.');
                }
                else
                    Message::error('A role with that name is already in use.');
            }
        }

        if(Input::post('update_perms'))
        {
            $post = Input::clean($_POST);

            User::permission()->where('role_id', '=', $role->id)->delete();

            if(isset($post['permissions']))
            {
                foreach($post['permissions'] as $permission)
                {
                    User::permission()->insert(array(
                        'role_id' => $id,
                        'permission' => $permission
                    ));
                }
            }

            Message::ok('Permissions updated successfully.');
        }

        $currentPerms = User::permission()->where('role_id', '=', $id)->all();

        $tmp = array();
        foreach($currentPerms as $cp)
            $tmp[] = $cp->permission;

        $currentPerms = $tmp;

        return array(
            'role' => $role,
            'permissions' => User::getSortedPermissions(),
            'currentPermissions' => $currentPerms
        );
    }

    /**
     * Deletes a role and redirects back to manage roles page.
     */
    public static function delete($id)
    {
        User::permission()->where('role_id', '=', $id)->delete();

        if(User::role()->delete($id))
            Message::ok('Role deleted successfully.');
        else
            Message::error('Error deleting role.');

        Url::redirect('admin/role/manage');
    }

}
