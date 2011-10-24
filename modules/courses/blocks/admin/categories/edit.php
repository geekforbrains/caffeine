<?php View::load('Courses', 'admin/sidenav'); ?>

<div class="area right">
    <h2>Edit Category</h2>
    <form method="post" action="<?php echo Router::current_url(); ?>">
        <ul>
            <li class="text small">
                <label>Name</label>
                <input type="text" name="name" value="<?php echo $category['name']; ?>" />
                <?php echo Validate::error('name'); ?>
            </li>
            <li class="textarea medium">
                <label>Short Description</label>
                <textarea name="short_desc"><?php echo $category['short_desc']; ?></textarea>
                <?php echo Validate::error('short_desc'); ?>
            </li>
            <li class="textarea medium">
                <label>Long Description</label>
                <textarea class="tinymce" name="long_desc"><?php echo $category['long_desc']; ?></textarea>
                <?php echo Validate::error('long_desc'); ?>
            </li>
            <li class="buttons">
                <input type="submit" value="Update Category" />
            </li>
        </ul>
    </form>
</div>
