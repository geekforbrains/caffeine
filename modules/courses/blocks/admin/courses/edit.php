<?php View::load('Courses', 'admin/sidenav'); ?>

<div class="area right">
    <div class="area">
        <h2>Edit Course</h2>
        <form method="post" action="<?php echo Router::current_url(); ?>">
            <ul>
                <li class="select small">
                    <label>Category</label>
                    <select name="category_cid">
                        <option value="">-</option>
                        <?php foreach($categories as $c): ?>
                            <?php $sel = ($c['cid'] == $course['category_cid']) ? 'selected="selected"' : ''; ?>
                            <option value="<?php echo $c['cid']; ?>" <?php echo $sel; ?>>
                                <?php echo $c['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php echo Validate::error('category_cid'); ?>
                </li>
                <li class="text small">
                    <label>Name</label>
                    <input type="text" name="name" value="<?php echo $course['name']; ?>" />
                    <?php echo Validate::error('name'); ?>
                </li>
                <li class="textarea medium">
                    <label>Short Description</label>
                    <textarea name="short_desc"><?php echo $course['short_desc']; ?></textarea>
                    <?php echo Validate::error('short_desc'); ?>
                </li>
                <li class="textarea medium">
                    <label>Long Description</label>
                    <textarea class="tinymce" name="long_desc"><?php echo $course['long_desc']; ?></textarea>
                    <?php echo Validate::error('long_desc'); ?>
                </li>
                <li class="textarea medium">
                    <label>What to Bring</label>
                    <textarea class="tinymce" name="what_to_bring"><?php echo $course['what_to_bring']; ?></textarea>
                    <?php echo Validate::error('what_to_bring'); ?>
                </li>
                <li class="text small">
                    <label>Course Length <em>(in days)</em></label>
                    <input type="text" name="length" value="<?php echo $course['length']; ?>">
                    <?php echo Validate::error('length'); ?>
                </li>
                <li class="text small">
                    <label>Start Date <em>Leave empty for TBD</em></label>
                    <input type="text" name="start_date" value="<?php echo date('M jS, Y', $course['start_date']); ?>" />
                    <?php echo Validate::error('start_date'); ?>
                </li>
                <li class="text small">
                    <label>End Date <em>Leave empty for TBD</em></label>
                    <input type="text" name="end_date" value="<?php echo date('M jS, Y', $course['end_date']); ?>" />
                    <?php echo Validate::error('end_date'); ?>
                </li>
                <li class="text small">
                    <label>Price</label>
                    <input type="text" name="price" value="<?php echo number_format($course['price'], 2); ?>" />
                    <?php echo Validate::error('price'); ?>
                </li>
                <li class="buttons">
                    <input type="submit" name="update_course" value="Update Course" />
                </li>
            </ul>
        </form>
    </div>

    <div class="area">
        <div class="area left">
            <h2>Manage Photos</h2>
            <table class="stripe" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th colspan="2">Thumbnail</th>
                </tr>

                <?php if($photos): ?>
                    <?php foreach($photos as $p): ?>
                        <tr valign="top">
                            <td><img src="<?php l('media/image/%d/0/75/75', $p['media_cid']); ?>" /></td>
                            <td align="right">
                                <a href="<?php l('admin/courses/courses/edit/%d/delete-photo/%d', $course['cid'], $p['media_cid']); ?>">
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
                    <li class="text small">
                        <label>Photo</label>
                        <input type="file" name="photo" />
                    </li>
                    <li class="buttons">
                        <input type="submit" name="upload_photo" value="Upload Photo" />
                    </li>
                </ul>
            </form>
        </div>
    </div>
</div>
