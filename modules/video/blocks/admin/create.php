<div class="area">
    <h2>Create Album</h2>
    <form method="post" action="<?php echo Router::current_url(); ?>">
        <ul>
            <li class="text small">
                <label>Name</label>
                <input type="text" name="name" />
                <?php echo Validate::error('name'); ?>
            </li>
            <li class="buttons">
                <input type="submit" value="Create Album" />
            </li>
        </ul>
    </form>
</div>
