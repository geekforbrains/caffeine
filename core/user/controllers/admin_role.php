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

        /*
        $form = Html::form()->addFieldset();

        $form->addText('name', array(
            'title' => 'Name',
            'value' => $role->name,
            'validate' => array('required')
        ));

        $form->addSubmit('update_role', 'Update Role');
        $form->addLink(Url::to('admin/role/manage'), 'Cancel');

        // -------------------------------------------------------

        $table = Html::table();
        $table->addHeader()->addCol('Module Roles', array('colspan' => 2));

        $sortedPermissions = User::getSortedPermissions();
        $setPermissions = User::permission()->where('role_id', '=', $id)->all();

        $tmp = array();
        foreach($setPermissions as $ap)
            $tmp[] = $ap->permission;

        $setPermissions = $tmp;

        foreach($sortedPermissions as $module => $modulePermissions)
        {
            $nameRow = $table->addRow();
            $nameRow->addCol(sprintf('<strong>%s</strong>', ucfirst($module)), array('colspan' => 2));

            foreach($modulePermissions as $permission => $desc)
            {
                $fieldRow = $table->addRow();

                $checked = in_array($permission, $setPermissions) ? ' checked="checked"' : '';

                $fieldRow->addCol(
                    '<input type="checkbox" name="permissions[]" value="' . $permission . '"' . $checked . ' />',
                    array('width' => 10)
                );

                $fieldRow->addCol($desc);
            }
        }

        // TODO This is a bit hacky, figure a better way to make tables into forms
        $tableHtml = Html::form()->open(null, 'post', false, array('name' => 'perms', 'id' => 'perms'));
        $tableHtml .= $table->render();
        $tableHtml .= '<div class="buttons">';
            $tableHtml .= '<input type="hidden" name="update_perms" value="true" />';
            //$tableHtml .= '<a class="btn " href="#">Update Permissions</a>';
            $tableHtml .= '<input class="btn btn-primary" type="submit" value="Update Permissions" />';
        $tableHtml .= '</div>';
        $tableHtml .= Html::form()->close();

        return array(
            array(
                'title' => 'Edit Role',
                'content' => $form->render()
            ),
            array(
                'title' => 'Edit Role Permissions', 
                'content' => $tableHtml
            )
        );
        */

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
