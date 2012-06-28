<? View::insert('includes/header'); ?>

<div class="row-fluid">
    <div class="span12">
        <div class="page-header"><h2>Create Users</h2></div>

        <form class="form-horizontal" method="post" action="<?= _current(); ?>">
            <fieldset>
                <div class="<?= _e('email')->getClass('control-group'); ?>">
                    <label class="control-label">Email</label>
                    <div class="controls">
                        <input type="text" name="email" value="<?= _p('email'); ?>" />

                        <? if(_e('email')->message): ?>
                            <span class="help-inline"><?= _e('email')->message; ?></span>
                        <? endif; ?>
                    </div>
                </div>

                <div class="<?= _e('pass')->getClass('control-group'); ?>">
                    <label class="control-label">Password</label>
                    <div class="controls">
                        <input type="password" name="pass" />

                        <? if(_e('pass')->message): ?>
                            <span class="help-inline"><?= _e('pass')->message; ?></span>
                        <? endif; ?>
                    </div>
                </div>

                <div class="<?= _e('pass_conf')->getClass('control-group'); ?>">
                    <label class="control-label">Confirm Password</label>
                    <div class="controls">
                        <input type="password" name="pass_conf" />

                        <? if(_e('pass_conf')->message): ?>
                            <span class="help-inline"><?= _e('pass_conf')->message; ?></span>
                        <? endif; ?>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label">Roles</label>
                    <div class="controls">
                        <select name="role_id[]" multiple="multiple">
                            <? if($roles): ?>
                                <? foreach($roles as $role): ?>
                                    <? $sel = in_array($role->id, _p('role_id', array())) ? ' selected="selected"' : ''; ?>
                                    <option value="<?= $role->id; ?>"<?= $sel; ?>>
                                        <?= $role->name; ?>
                                    </option>
                                <? endforeach; ?>
                            <? endif; ?>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <input type="submit" class="btn btn-primary" name="create_user" value="Create User" />
                    <a class="btn" href="<?= _to('admin/user/manage'); ?>">Cancel</a>
                </div>
            </fieldset>
        </form>
    </div>
</div>

<? View::insert('includes/footer'); ?>
