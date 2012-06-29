<? View::insert('includes/header'); ?>

<div class="row-fluid">
    <div class="span12">
        <div class="page-header"><h2>Create Role</h2></div>

        <form class="form-horizontal" method="post" action="<?= _current(); ?>">
            <fieldset>
                <div class="<?= _e('name')->getClass('control-group'); ?>">
                    <label class="control-label">Name</label>
                    <div class="controls">
                        <input type="text" name="name" value="<?= _p('name'); ?>" />

                        <? if(_e('name')->message): ?>
                            <span class="help-inline"><?= _e('name')->message; ?></span>
                        <? endif; ?>
                    </div>
                </div>

                <div class="form-actions">
                    <input type="submit" class="btn btn-primary" name="create_role" value="Create Role" />
                    <a class="btn" href="<?= _to('admin/role/manage'); ?>">Cancel</a>
                </div>
            </fieldset>
        </form>
    </div>
</div>

<? View::insert('includes/footer'); ?>
