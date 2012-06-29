<? View::insert('includes/header'); ?>

<div class="row-fluid"> <div class="span12">
        <div class="page-header"><h2>Create Page</h2></div>

        <form class="form-horizontal" method="post" action="<?= _current(); ?>">
            <fieldset>
                <div class="<?= _e('title')->getClass('control-group'); ?>">
                    <label class="control-label">Title</label>
                    <div class="controls">
                        <input type="text" name="title" value="<?= _p('title'); ?>" />

                        <? if(_e('title')->message): ?>
                            <span class="help-inline"><?= _e('title')->message; ?></span>
                        <? endif; ?>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label">Parent Page</label>
                    <div class="controls">
                        <select name="page_id">
                            <option value="0">None</option>
                            
                            <? if($pages): ?>
                                <? foreach($pages as $p): ?>
                                    <option value="<?= $p->id; ?>">
                                        <?= $p->indent . $p->title; ?>
                                    </option>
                                <? endforeach; ?>
                            <? endif; ?>
                        </select>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label">Body</label>
                    <div class="controls">
                        <div class="span10">
                            <textarea class="wysiwyg" rows="10" name="body"><?= _p('body'); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <input type="submit" class="btn btn-primary" name="create_page" value="Create Page" />
                    <input type="submit" class="btn btn-inverse" name="save_draft" value="Save as Draft" />
                    <a class="btn" href="<?= _to('admin/page/manage'); ?>">Cancel</a>
                </div>
            </fieldset>
        </form>
    </div>
</div>

<? View::insert('includes/footer'); ?>
