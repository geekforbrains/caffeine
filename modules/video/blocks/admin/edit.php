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
                <input type="submit" value="Update Album" name="update_album" />
            </li>
        </ul>
    </form>
</div>

<div class="area">
    <h2>Add Video</h2>
    <form method="post" action="<?php echo Router::current_url(); ?>">
        <ul>
            <li class="text small">
                <label>URL</label>
                <input type="text" name="url" />
                <?php echo Validate::error('url'); ?>
            </li>
            <li class="buttons">
                <input type="submit" value="Add Video" name="add_video" />
            </li>
        </ul>
    </form>
</div>

<div class="area">
    <h2>Manage Videos</h2>
    <table class="stripe" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <th colspan="3">Video</th>
        </tr>

        <?php if($videos): ?>
            <?php foreach($videos as $v): ?>
                <tr valign="top">
                    <td width="75">
                        <a href="<?php echo $v['url']; ?>" target="_blank">
                            <img src="<?php l('media/image/%d/0/75/75', $v['media_cid']); ?>" />
                        </a>
                    </td>
                    <td>
                        <a href="<?php echo $v['url']; ?>" target="_blank">
                            <?php echo $v['title']; ?>
                        </a>
                    </td>
                    <td align="right">
                        <a href="<?php l('admin/video/edit/%d/delete-video/%d', $album['cid'], $v['cid']); ?>">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3"><em>No videos.</em></td></tr>
        <?php endif; ?>
    </table>
</div>
