<?php View::load('Ads', 'admin/sidenav'); ?>

<div class="area right">
    <h2>Create Ad</h2>
    <form method="post" action="<?php echo Router::current_url(); ?>" enctype="multipart/form-data">
        <ul>
            <li class="text small">
                <label>Name</label>
                <input type="text" name="name" value="<?php echo Input::post('name'); ?>" />
                <?php echo Validate::error('name'); ?>
            </li>
            <li class="select small">
                <label>Area</label>
                <select name="area_cid">
                    <option value="">-</option>
                    <?php foreach($areas as $a): ?>
                        <option value="<?php echo $a['cid']; ?>">
                            <?php echo $a['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php echo Validate::error('area_cid'); ?>
            </li>
            <li class="text small">
                <label>Image</label>
                <input type="file" name="image" />
            </li>
            <li class="textarea medium">
                <label>URL</label>
                <textarea name="url"><?php echo Input::post('url'); ?></textarea>
                <?php echo Validate::error('url'); ?>
            </li>
            <li class="buttons">
                <input type="submit" value="Create Ad" />
            </li>
        </ul>
    </form>
</div>
