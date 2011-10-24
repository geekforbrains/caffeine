<?php View::load('Courses', 'admin/sidenav'); ?>

<div class="area right">
    <h2>Create Course</h2>
    <form method="post" action="<?php echo Router::current_url(); ?>">
        <ul>
            <li class="select small">
                <label>Category</label>
                <select name="category_cid">
                    <option value="">-</option>
                    <?php foreach($categories as $c): ?>
                        <option value="<?php echo $c['cid']; ?>">
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
                <label>Short Description</label>
                <textarea name="short_desc"><?php echo Input::post('short_desc'); ?></textarea>
                <?php echo Validate::error('short_desc'); ?>
            </li>
            <li class="textarea medium">
                <label>Long Description</label>
                <textarea class="tinymce" name="long_desc"><?php echo Input::post('long_desc'); ?></textarea>
                <?php echo Validate::error('long_desc'); ?>
            </li>
            <li class="textarea medium">
                <label>What to Bring</label>
                <textarea class="tinymce" name="what_to_bring"><?php echo Input::post('what_to_bring'); ?></textarea>
                <?php echo Validate::error('what_to_bring'); ?>
            </li>
            <li class="text small">
                <label>Course Length <em>(in days)</em></label>
                <input type="text" name="length" value="<?php echo Input::post('length'); ?>">
                <?php echo Validate::error('length'); ?>
            </li>
            <li class="text small">
                <label>Start Date <em>Leave empty for TBD</em></label>
                <input type="text" name="start_date" value="<?php echo Input::post('start_date'); ?>" />
                <?php echo Validate::error('start_date'); ?>
            </li>
            <li class="text small">
                <label>End Date <em>Leave empty for TBD</em></label>
                <input type="text" name="end_date" value="<?php echo Input::post('end_date'); ?>" />
                <?php echo Validate::error('end_date'); ?>
            </li>
            <li class="text small">
                <label>Price</label>
                <input type="text" name="price" value="<?php echo Input::post('price'); ?>" />
                <?php echo Validate::error('price'); ?>
            </li>
            <li class="buttons">
                <input type="submit" value="Create Course" />
            </li>
        </ul>
    </form>
</div>
