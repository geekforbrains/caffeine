<?php View::load('Portfolio', 'admin/sidenav'); ?>

<div class="area right">
    <h2>Create Category</h2>
    <form method="post" action="<?php echo Router::current_url(); ?>">
        <ul>
            <li class="text small">
                <label>Name</label>
                <input type="text" name="name" />
                <?php echo Validate::error('name'); ?>
            </li>
            <li class="buttons">
                <input type="submit" value="Create Category" />
            </li>
        </ul>
    </form>
</div>
