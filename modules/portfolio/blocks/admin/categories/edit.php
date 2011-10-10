<?php View::load('Portfolio', 'admin/sidenav'); ?>

<div class="area right">
    <h2>Edit Category</h2>
    <form method="post" action="<?php echo Router::current_url(); ?>">
        <ul>
            <li class="text small">
                <label>Name</label>
                <input type="text" name="name" value="<?php echo $category['name']; ?>" />
                <?php echo Validate::error('name'); ?>
            </li>
            <li class="buttons">
                <input type="submit" value="Update Category" />
            </li>
        </ul>
    </form>
</div>
