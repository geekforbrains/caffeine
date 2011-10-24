<?php View::load('Courses', 'admin/sidenav'); ?>

<div class="area right">
    <h2>Create Category</h2>
    <form method="post" action="<?php echo Router::current_url(); ?>">
        <ul>
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
                <textarea name="long_desc"><?php echo Input::post('long_desc'); ?></textarea>
                <?php echo Validate::error('long_desc'); ?>
            </li>
            <li class="buttons">
                <input type="submit" value="Create Category" />
            </li>
        </ul>
    </form>
</div>
