<? View::insert('includes/header'); ?>

<div class="row-fluid">
    <div class="span12">
        <div class="page-header">
            <? if(User::current()->id == $user->id): ?>
                <h2>My Profile</h2>
            <? else: ?>
                <h2>Edit User</h2>
            <? endif; ?>
        </div>

        <form class="form-horizontal" method="post" action="<?= _current(); ?>">
            <fieldset>
                <div class="<?= _e('email')->getClass('control-group'); ?>">
                    <label class="control-label">Email</label>
                    <div class="controls">
                        <input type="text" name="email" value="<?= $user->email; ?>" />
                    </div>
                </div>

                <div class="<?= _e('pass')->getClass('control-group'); ?>">
                    <label class="control-label">New Password</label>
                    <div class="controls">
                        <input type="password" name="pass" />
                    </div>
                </div>

                <div class="<?= _e('pass')->getClass('control-group'); ?>">
                    <label class="control-label">Confirm Password</label>
                    <div class="controls">
                        <input type="password" name="pass_conf" />
                    </div>
                </div>

                <? if(User::current()->hasPerm('user.edit_profile_roles')): ?>
                    <div class="<?= _e('pass')->getClass('control-group'); ?>">
                        <label class="control-label">Roles</label>
                        <div class="controls">
                            <select name="role_id[]" multiple="multiple">
                                <? if($roles): ?>
                                    <? foreach($roles as $role): ?>
                                        <? $sel = $user->hasRole($role->id) ? ' selected="selected"' : ''; ?>
                                        <option value="<?= $role->id; ?>"<?= $sel; ?>>
                                            <?= $role->name; ?>
                                        </option>
                                    <? endforeach; ?>
                                <? endif; ?>
                            </select>
                        </div>
                    </div>
                <? endif; ?>

                <div class="form-actions">
                    <input type="submit" class="btn btn-primary" name="update_user" 
                        value="Update <?= User::current()->id == $user->id ? 'Profile' : 'User'; ?>" />

                    <a class="btn" href="<?= _to('admin/user/manage'); ?>">Cancel</a>
                </div>
            </fieldset>
        </form>
    </div>
</div>

<? View::insert('includes/footer'); ?>
