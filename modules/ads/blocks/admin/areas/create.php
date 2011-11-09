<?php View::load('Ads', 'admin/sidenav'); ?>

<div class="area right">
    <h2>Create Area</h2>
    <form method="post" action="<?php echo Router::current_url(); ?>">
        <ul>
            <li class="text small">
                <label>Name</label>
                <input type="text" name="name" value="<?php echo Input::post('name'); ?>" />
                <?php echo Validate::error('name'); ?>
            </li>
            <li class="text small">
                <label>Slug</label>
                <input type="text" name="slug" value="<?php echo Input::post('slug'); ?>" />
                <?php echo Validate::error('slug'); ?>
            </li>
            <li class="text short">
                <label>Image Size <em>(Width x Height)</em></label>
                <input type="text" name="width" value="<?php echo Input::post('width'); ?>" /> x
                <input type="text" name="height" value="<?php echo Input::post('height'); ?>" />
                <?php echo Validate::error('width'); ?>
                <?php echo Validate::error('height'); ?>
            </li>
            <li class="buttons">
                <input type="submit" value="Create Area" />
            </li>
        </ul>
    </form>
</div>
