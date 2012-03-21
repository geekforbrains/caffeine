<?php

class User_Admin_RoleController extends Controller {

    /**
     * Displays a table for managing users.
     */
    public static function manage()
    {
        $table = Html::table();
        $table->addHeader()->addCol('Role', array('colspan' => 2));

        if($roles = User::role()->all())
        {
            foreach($roles as $role)
            {
                $row = $table->addRow();
                $row->addCol(Html::a()->get($role->name, 'admin/user/role/edit/' . $role->id));
                $row->addCol(
                    Html::a('Delete', 'admin/user/role/delete/' . $role->id, array('onclick' => "return confirm('Delete this role?')")),
                    array('class' => 'right')
                );
            }
        }
        else
            $table->addRow()->addCol('<em>No roles.</em>', array('colspan' => 2));

        return array(
            'title' => 'Manage Roles',
            'content' => $table->render()
        );
    }

    /**
     * Displays a form for creating new roles.
     */
    public static function create()
    {
        if(isset($_POST['create_role']) && Html::form()->validate())
        {
            if(!User::role()->where('name', 'LIKE', '%' . $_POST['name'] . '%')->first())
            {
                $roleId = User::role()->insert(array(
                    'name' => $_POST['name']
                ));

                if($roleId)
                {
                    Message::ok('Role created successfully.');
                    Url::redirect('admin/user/role/edit/' . $roleId);
                }
                else
                    Message::error('Error creating role.');
            }
            else
                Message::error('A role with that name already exists.');
        }

        $fields[] = array(
            'fields' => array(
                'name' => array(
                    'title' => 'Name',
                    'type' => 'text',
                    'validate' => array('required')
                ),
                'create_role' => array(
                    'value' => 'Create Role',
                    'type' => 'submit'
                )
            )
        );

        return array(
            'title' => 'Create Role',
            'content' => Html::form()->build($fields)
        );
    }

    /**
     * Displays a form for updating a role name and a table of available permissions that
     * can be checked to add to the role.
     */
    public static function edit($id)
    {
        $role = User::role()->find($id);

        if(isset($_POST['update_role']))
        {
            if($_POST['name'] == $role->name || !User::role()->where('name', 'LIKE', $_POST['name'])->first())
            {
                $status = User::role()->where('id', '=', $id)->update(array(
                    'name' => $_POST['name']
                ));
                
                if($status)
                {
                    Message::ok('Role updated successfully.');
                    $role->name = $_POST['name']; // Set updated name for form
                }
                else
                    Message::info('Nothing changed.');
            }
            else
                Message::error('A role with that name is already in use.');
        }

        if(isset($_POST['update_perms']))
        {
            User::permission()->where('role_id', '=', $role->id)->delete();

            if(isset($_POST['permissions']))
            {
                foreach($_POST['permissions'] as $permission)
                {
                    User::permission()->insert(array(
                        'role_id' => $id,
                        'permission' => $permission
                    ));
                }
            }

            Message::ok('Permissions updated successfully.');
        }

        $fields[] = array(
            'fields' => array(
                'name' => array(
                    'title' => 'Name',
                    'type' => 'text',
                    'default_value' => $role->name,
                    'validate' => array('required')
                ),
                'update_role' => array(
                    'value' => 'Update Role',
                    'type' => 'submit'
                )
            )
        );

        $formHtml = Html::form()->build($fields);

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
            $tableHtml .= '<a class="blue btn submitter" href="#">Update Permissions</a>';
        $tableHtml .= '</div>';
        $tableHtml .= Html::form()->close();

        return array(
            array(
                'title' => 'Edit Role',
                'content' => $formHtml
            ),
            array(
                'title' => 'Edit Role Permissions', 
                'content' => $tableHtml
            )
        );
    }

    /**
     * Deletes a role and redirects back to manage roles page.
     */
    public static function delete($id)
    {
        if(User::role()->delete($id))
            Message::ok('Role deleted successfully.');
        else
            Message::error('Error deleting role.');

        Url::redirect('admin/user/role/manage');
    }

}
