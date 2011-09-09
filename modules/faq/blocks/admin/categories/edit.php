<div class="area left short">
    <h3>Navigation</h2>
    <?php echo Menu::build('admin/faq/categories', 0); ?>
</div>

<div class="area right">
    <h2>Edit Category</h2>
    <form method="post" action="<?php echo Router::current_url(); ?>">
        <ul>
            <li class="text small">
                <label>Category Name</label>
                <input type="text" name="name" value="<?php echo $category['name']; ?>" />
                <?php echo Validate::error('name'); ?>
            </li>
            <li class="buttons">
                <input type="submit" value="Update Category" />
            </li>
        </ul>
    </form>
</div>
