<?php

class User_User_Role_AdminController extends Controller {

    public static function manage()
    {
        $rows = array();
        $headers = array(
            array(
                'Role',
                'attributes' => array('colspan' => 2)
            )
        );

        if($roles = User::role()->all())
        {
            foreach($roles as $role)
            {
                $rows[] = array(
                    Html::a()->get($role->name, 'admin/user/role/edit/' . $role->id),
                    array(
                        Html::a()->get('Delete', 'admin/user/role/delete/' . $role->id),
                        'attributes' => array('align' => 'right')
                    )
                );
            }
        }
        else
        {
            $rows[] = array(
                array(
                    '<em>No roles.</em>',
                    'attributes' => array('colspan' => 2)
                )
            );
        }

        return Html::table()->build($headers, $rows);
    }

    public static function create()
    {
        if($_POST)
        {
            if(!User::role()->where('name', 'LIKE', '%' . $_POST['name'] . '%')->first())
            {
                $role = User::role();
                $role->name = $_POST['name'];

                if($role->save())
                {
                    Message::ok('Role created successfully.');
                    Url::redirect('admin/user/role/edit/' . $role->id);
                }
                else
                    Message::error('Error creating role.');
            }
            else
                Message::error('A role with that name already exists.');
        }

        $fields = array(
            'name' => array(
                'title' => 'Name',
                'type' => 'text'
            ),
            'submit' => array(
                'value' => 'Create Role',
                'type' => 'submit'
            )
        );

        return Html::form()->build($fields);
    }

    public static function edit($id)
    {
        if($_POST)
        {
            if(isset($_POST['update_role']))
            {
                // Check if name is in use
            }

            if(isset($_POST['update_roles']))
            {
                // Clear permissions associated with this role and re-insert all new ones
                User::role()->find($id)->permission()->delete();

                foreach($_POST['permissions'] as $permission)
                {
                    $permission = User::permission();
                }
            }
        }

        $role = User::role()->find($id);

        $fields = array(
            'name' => array(
                'title' => 'Name',
                'type' => 'text',
                'default_value' => $role->name
            ),
            'update_role' => array(
                'value' => 'Update Role',
                'type' => 'submit'
            )
        );

        $formHtml = Html::form()->build($fields);

        $rows = array();
        $headers = array(
            array(
                'Module Roles',
                'attributes' => array('colspan' => 2)
            )
        );

        $permissions = User::getSortedPermissions();

        foreach($permissions as $module => $modulePermissions)
        {
            $rows[] = array(
                array(
                    sprintf('<strong>%s</strong>', ucfirst($module)),
                    'attributes' => array('colspan' => 2)
                )
            );

            foreach($modulePermissions as $permission => $desc)
            {
                $rows[] = array(
                    array(
                        '<input type="checkbox" name="permissions[]" value="' . $permission . '" />',
                        'attributes' => array('width' => 10)
                    ),
                    $desc
                );
            }
        }

        // This is a bit hacky, figure a better way to make tables into forms
        $tableHtml = Html::form()->open();
        $tableHtml .= Html::table()->build($headers, $rows, array('class' => 'stripe'));
        $tableHtml .= '<div class="buttons"><input type="submit" name="update_roles" value="Update Permissions" /></div>';
        $tableHtml .= Html::form()->close();

        return array($formHtml, array('title' => 'Edit Role Permissions', 'content' => $tableHtml));
    }

    public static function delete($id)
    {
        if(User::role()->delete($id))
            Message::ok('Role deleted successfully.');
        else
            Message::error('Error deleting role.');

        Url::redirect('admin/user/role/manage');
    }

}
