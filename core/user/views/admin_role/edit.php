<? View::insert('includes/header'); ?>

<div class="row-fluid">
    <div class="span12">
        <div class="page-header"><h2>Edit Role</h2></div>

        <form class="form-horizontal" method="post" action="<?= _current(); ?>">
            <fieldset>
                <div class="<?= _e('name')->getClass('control-group'); ?>">
                    <label class="control-label">Name</label>
                    <div class="controls">
                        <input type="text" name="name" value="<?= $role->name; ?>" />

                        <? if(_e('name')->message): ?>
                            <span class="help-inline"><?= _e('name')->message; ?></span>
                        <? endif; ?>
                    </div>
                </div>

                <div class="form-actions">
                    <input type="submit" class="btn btn-primary" name="update_role" value="Update Role" />
                    <a class="btn" href="<?= _to('admin/role/manage'); ?>">Cancel</a>
                </div>
            </fieldset>
        </form>
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="page-header"><h2>Edit Permissions</h2></div>

        <form method="post" action="<?= _current(); ?>">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th colspan="2">Permission</th>
                    </tr>
                </thead>
                <tbody>
                    <? if($permissions): ?>
                        <? foreach($permissions as $module => $perms): ?>
                            <tr><td colspan="2"><?= ucfirst($module); ?></td></tr>

                            <? foreach($perms as $perm => $desc): ?>
                                <tr>
                                    <td width="10">
                                        <? $check = in_array($perm, $currentPermissions) ? 'checked="checked" ' : null; ?>
                                        <input type="checkbox" name="permissions[]" value="<?= $perm ?>" <?= $check; ?>/>
                                    </td>
                                    <td><?= $desc ?></td>
                                </tr>
                            <? endforeach; ?>
                        <? endforeach; ?>
                    <? else: ?>
                        <tr><td colspan="2"><em>No permissions.</em></td></tr>
                    <? endif; ?>
                </tbody>
            </table>

            <input class="btn btn-primary" type="submit" name="update_perms" value="Update Permissions" />
        </form>
    </div>
</div>

<? View::insert('includes/footer'); ?>
