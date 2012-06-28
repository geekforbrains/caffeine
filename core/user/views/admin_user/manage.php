<? View::insert('includes/header'); ?>

<div class="row-fluid">
    <div class="span12">
        <div class="page-header"><h2>Manage Users</h2></div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th colspan="2">User</th>
                </tr>
            </thead>
            <tbody>
                <? if($users): ?>
                    <? foreach($users as $user): ?>
                        <tr>
                            <td><?= _a($user->email, 'admin/user/edit/' . $user->id); ?></td>
                            <td class="right">
                                <? if(!$user->is_admin): ?>
                                    <?= _a('Delete', 'admin/user/delete/' . $user->id); ?>
                                <? endif; ?>
                            </td>
                        </tr>
                    <? endforeach; ?>
                <? else: ?>
                    <tr><td colspan="2"><em>No users.</em></td></tr>
                <? endif; ?>
            </tbody>
        </table>
    </div>
</div>

<? View::insert('includes/footer'); ?>
