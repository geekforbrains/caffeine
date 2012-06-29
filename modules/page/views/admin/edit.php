<? View::insert('includes/header'); ?>

<div class="row-fluid">
    <div class="span12">
        <div class="page-header"><h2>Edit Page</h2></div>

        <form class="form-horizontal" method="post" action="<?= _current(); ?>">
            <fieldset>
                <div class="<?= _e('title')->getClass('control-group'); ?>">
                    <label class="control-label">Title</label>
                    <div class="controls">
                        <input type="text" name="title" value="<?= _p('title', $page->title); ?>" />

                        <? if(_e('title')->message): ?>
                            <span class="help-inline"><?= _e('title')->message; ?></span>
                        <? endif; ?>
                    </div>
                </div>

                <div class="<?= _e('slug')->getClass('control-group'); ?>">
                    <label class="control-label">Slug</label>
                    <div class="controls">
                        <input type="text" name="slug" value="<?= _p('slug', $page->slug); ?>" />

                        <? if(_e('slug')->message): ?>
                            <span class="help-inline"><?= _e('slug')->message; ?></span>
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
                                    <? $sel = $p->id == $page->page_id ? ' selected="selected"' : null; ?>
                                    <option value="<?= $p->id; ?>"<?= $sel; ?>>
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
                            <textarea class="wysiwyg" rows="10" name="body"><?= _p('body', $page->body); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <input type="submit" class="btn btn-primary" name="update_page" value="Update Page" />
                    <input type="submit" class="btn btn-inverse" name="save_draft" value="Save as Draft" />
                    <a class="btn" href="<?= _to('admin/page/manage'); ?>">Cancel</a>
                </div>
            </fieldset>
        </form>
    </div>
</div>

<? View::insert('includes/footer'); ?>
