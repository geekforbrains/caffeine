<div class="area">
    <h2>Edit Album</h2>
    <form method="post" action="<?php echo Router::current_url(); ?>">
        <ul>
            <li class="text small">
                <label>Name</label>
                <input type="text" name="name" value="<?php echo $album['name']; ?>" />
                <?php echo Validate::error('name'); ?>
            </li>
            <li class="buttons">
                <input type="submit" name="update_album" value="Update Album" />
            </li>
        </ul>
    </form>
</div>

<div class="area">
    <h2>Upload Photo</h2>
    <form method="post" action="<?php echo Router::current_url(); ?>" enctype="multipart/form-data">
        <ul>
            <li class="text small">
                <label>Photo</label>
                <input type="file" name="photo" />
            </li>
            <li class="text small">
                <label>Title</label>
                <input type="text" name="title" />
            </li>
            <li class="textarea medium">
                <label>Description</label>
                <textarea name="description"></textarea>
            </li>
            <li class="buttons">
                <input type="submit" name="upload_photo" value="Upload Photo" />
            </li>
        </ul>
    </form>
</div>

<div class="area">
    <h2>Manage Photos</h2>
    <table class="stripe" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <th>Photo</th>
            <th>Title</th>
            <th colspan="2">Description</th>
        </tr>

        <?php if($photos): ?>
            <?php foreach($photos as $p): ?>
                <tr valign="top">
                    <td width="75">
                        <a href="<?php l('admin/gallery/edit/%d/edit-photo/%d', $album['cid'], $p['cid']); ?>">
                            <img src="<?php l('media/image/%d/0/75/75', $p['media_cid']); ?>" />
                        </a>
                    </td>
                    <td>
                        <a href="<?php l('admin/gallery/edit/%d/edit-photo/%d', $album['cid'], $p['cid']); ?>">
                            <?php echo $p['title']; ?>
                        </a>
                    </td>
                    <td><?php echo substr($p['description'], 0, 150); ?></td>
                    <td align="right">
                        <a href="<?php l('admin/gallery/edit/%d/delete-photo/%d', $album['cid'], $p['cid']); ?>">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4"><em>No photos.</em></td></tr>
        <?php endif; ?>
    </table>
</div>
