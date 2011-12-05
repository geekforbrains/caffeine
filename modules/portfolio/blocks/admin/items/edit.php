<?php View::load('Portfolio', 'admin/sidenav'); ?>

<div class="area right">
    <div class="area">
        <h2>Edit Item</h2>
        <form method="post" action="<?php echo Router::current_url(); ?>" enctype="multipart/form-data">
            <ul>
                <li class="select small">
                    <label>Category</label>
                    <select name="category_cid">
                        <option value="">-</option>
                        <?php foreach($categories as $c): ?>
                            <?php $sel = ($c['cid'] == $item['category_cid']) ? 'selected="selected"' : ''; ?>
                            <option value="<?php echo $c['cid']; ?>" <?php echo $sel; ?>>
                                <?php echo $c['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php echo Validate::error('category_cid'); ?>
                </li>
                <li class="text small">
                    <label>Name</label>
                    <input type="text" name="name" value="<?php echo $item['name']; ?>" />
                    <?php echo Validate::error('name'); ?>
                </li>
                <li class="text small">
                    <label>Slug</label>
                    <input type="text" name="slug" value="<?php echo $item['slug']; ?>" />
                    <?php echo Validate::error('slug'); ?>
                </li>
                <li class="textarea medium">
                    <label>Description</label>
                    <textarea name="description" class="tinymce"><?php echo $item['description']; ?></textarea>
                </li>
                <li class="text small">
                    <label>Thumbnail</label>
                    <input type="file" name="thumb" />

                    <?php if($item['thumb_cid'] > 0): ?>
                        <br /><br />
                        
                        <img src="<?php l('media/image/%d/0/75/75', $item['thumb_cid']); ?>" />
                    <?php endif; ?>
                </li>

                <!-- start extra data fields -->

                <li class="text small">
                    <label>Client</label>
                    <input type="text" name="client" value="<?php echo $item['client']; ?>" />
                </li>
                <li class="text small">
                    <label>Role</label>
                    <input type="text" name="role" value="<?php echo $item['role']; ?>" />
                </li>
                <li class="text small">
                    <label>Date</label>
                    <input type="text" name="date" value="<?php echo date('M jS, Y', $item['date']); ?>" />
                </li>
	                <li class="text small">
	                    <label>Weight</label>
	                    <input type="text" name="weight" value="<?php echo $item['weight']; ?>" />
	                </li>

                <!-- end extra data fields -->

                <li class="buttons">
                    <input type="submit" name="update_item" value="Update Item" />
                </li>
            </ul>
        </form>
    </div>

    <div class="area">
        <div class="area left">
            <h2>Manage Photos</h2>
            <table class="stripe" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <th colspan="2">Photo</th>
                </tr>
                
                <?php if($photos): ?>
                    <?php foreach($photos as $p): ?>
                        <tr valign="top">
                            <td>
                                <a href="<?php l('media/image/%d', $p['cid']); ?>" target="_blank">
                                    <img src="<?php l('media/image/%d/0/75/75', $p['cid']); ?>" />
                                </a>
                            </td>
                            <td align="right">
                                <a href="<?php l('admin/portfolio/items/edit/%d/delete-photo/%d', $item['cid'], $p['cid']); ?>">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="2"><em>No photos.</em></td></tr>
                <?php endif; ?>
            </table>
        </div>

        <div class="area right">
            <h2>Upload Photo</h2>
            <form method="post" action="<?php echo Router::current_url(); ?>" enctype="multipart/form-data">
                <ul>
                    <li class="text large">
                        <label>Select Photo</label>
                        <input type="file" name="photo" />
                    </li>
                    <li class="buttons">
                        <input type="submit" name="upload_photo" value="Upload Photo" />
                    </li>
                </ul>
            </form>
        </div>
    </div>

    <div class="area">
        <div class="area left">
            <h2>Manage Videos</h2>
            <table class="stripe" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <th>Video</th>
                    <th colspan="2">Title</th>
                </tr>

                <?php if($videos): ?>
                    <?php foreach($videos as $v): ?>
                        <tr valign="top">
                            <td>
                                <a href="<?php echo $v['url']; ?>" target="_blank">
                                    <img src="<?php l('media/image/%d/0/75/75', $v['media_cid']); ?>" /></td>
                                </a>
                            </td>
                            <td>
                                <a href="<?php echo $v['url']; ?>" target="_blank">
                                    <?php echo $v['title']; ?>
                                </a>
                            </td>
                            <td align="right">
                                <a href="<?php l('admin/portfolio/items/edit/%d/delete-video/%d', $item['cid'], $v['cid']); ?>">
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

        <div class="area right">
            <h2>Add Video</h2>
            <form method="post" action="<?php echo Router::current_url(); ?>">
                <ul>
                    <li class="text large">
                        <label>Video URL <em>(YouTube or Vimeo)</em></label>
                        <input type="text" name="url" />
                    </li>
                    <li class="buttons">
                        <input type="submit" name="add_video" value="Add Video" />
                    </li>
                </ul>
            </form>
        </div>
    </div>
</div>
