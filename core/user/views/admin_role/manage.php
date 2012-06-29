<? View::insert('includes/header'); ?>

<div class="row-fluid">
    <div class="span12">
        <div class="page-header"><h2>Manage Roles</h2></div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th colspan="2">Role</th>
                </tr>
            </thead>
            <tbody>
                <? if($roles): ?>
                    <? foreach($roles as $role): ?>
                        <tr>
                            <td><?= _a($role->name, 'admin/role/edit/' . $role->id); ?></td>
                            <td class="right"><?= _a('Delete', 'admin/role/delete/' . $role->id); ?></td>
                        </tr>
                    <? endforeach; ?>
                <? else: ?>
                    <tr><td colspan="2"><em>No roles.</em></td></tr>
                <? endif; ?>
            </tbody>
        </table>
    </div>
</div>

<? View::insert('includes/footer'); ?>
