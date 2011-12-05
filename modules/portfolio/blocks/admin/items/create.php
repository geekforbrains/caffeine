<?php View::load('Portfolio', 'admin/sidenav'); ?>

<div class="area right">
    <h2>Create Item</h2>
    <form method="post" action="<?php echo Router::current_url(); ?>" enctype="multipart/form-data">
        <ul>
            <li class="select small">
                <label>Category</label>
                <select name="category_cid">
                    <option value="">-</option>
                    <?php foreach($categories as $c): ?>
                        <?php $sel = ($c['cid'] == Input::post('category_cid')) ? ' selected="selected"' : ''; ?>
                        <option value="<?php echo $c['cid']; ?>"<?php echo $sel; ?>>
                            <?php echo $c['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php echo Validate::error('category_cid'); ?>
            </li>
            <li class="text small">
                <label>Name</label>
                <input type="text" name="name" value="<?php echo Input::post('name'); ?>" />
                <?php echo Validate::error('name'); ?>
            </li>
            <li class="textarea medium">
                <label>Description</label>
                <textarea name="description" class="tinymce"><?php echo Input::post('description'); ?></textarea>
            </li>
            <li class="text small">
                <label>Thumbnail</label>
                <input type="file" name="thumb" />
            </li>

            <!-- start extra data fields -->

            <li class="text small">
                <label>Client</label>
                <input type="text" name="client" value="<?php echo Input::post('client'); ?>" />
            </li>
            <li class="text small">
                <label>Role</label>
                <input type="text" name="role" value="<?php echo Input::post('role'); ?>" />
            </li>
            <li class="text small">
                <label>Date</label>
                <input type="text" name="date" value="<?php echo Input::post('date'); ?>" />
            </li>
            <li class="text small">
                <label>Weight</label>
                <input type="text" name="weight" />
            </li>

            <!-- end extra data fields -->

            <li class="buttons">
                <input type="submit" value="Create Item" />
            </li>
        </ul>
    </form>
</div>
