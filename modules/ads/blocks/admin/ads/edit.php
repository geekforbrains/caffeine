<?php View::load('Ads', 'admin/sidenav'); ?>

<div class="area right">
    <h2>Edit Ad</h2>
    <form method="post" action="<?php echo Router::current_url(); ?>" enctype="multipart/form-data">
        <input type="hidden" name="media_cid" value="<?php echo $ad['media_cid']; ?>" />
        <ul>
            <li class="text small">
                <label>Name</label>
                <input type="text" name="name" value="<?php echo $ad['name']; ?>" />
                <?php echo Validate::error('name'); ?>
            </li>
            <li class="select small">
                <label>Area</label>
                <select name="area_cid">
                    <option value="">-</option>
                    <?php foreach($areas as $a): ?>
                        <?php $sel = ($ad['area_cid'] == $a['cid']) ? ' selected="selected"' : ''; ?>
                        <option value="<?php echo $a['cid']; ?>" <?php echo $sel; ?>>
                            <?php echo $a['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php echo Validate::error('area_cid'); ?>
            </li>
            <li class="text small">
                <label>Image</label>
                <input type="file" name="image" />

                <br /><br />

                <img src="<?php l('media/image/%d/0/%d/%d', $ad['media_cid'], $ad['image_width'], $ad['image_height']); ?>" />
            </li>
            <li class="textarea medium">
                <label>URL</label>
                <textarea name="url"><?php echo $ad['url']; ?></textarea>
                <?php echo Validate::error('url'); ?>
            </li>
            <li class="buttons">
                <input type="submit" value="Update Ad" />
            </li>
        </ul>
    </form>
</div>
