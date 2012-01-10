<div class="area">
    <h2>Edit Photo</h2>
    <form method="post" action="<?php echo Router::current_url(); ?>">
        <ul>
            <li>
                <!--<img src="<?php l('media/image/%d/0/125/125', $photo['media_cid']); ?>" />-->
                <img src="<?php echo Media::image($photo['media_cid'], 0, 125, 125); ?>" />
            </li>
            <li class="text small">
                <label>Title</label>
                <input type="text" name="title" value="<?php echo $photo['title']; ?>" />
            </li>
            <li class="textarea medium">
                <label>Description</label>
                <textarea name="description"><?php echo $photo['description']; ?></textarea>
            </li>
            <li class="buttons">
                <input type="submit" value="Update Photo" />
            </li>
        </ul>
    </form>
</div>
