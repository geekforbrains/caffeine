<div class="area">
    <h2>Edit Category</h2>
    <form method="post" action="<?php echo Router::current_url(); ?>">
        <ul>
            <li class="select medium">
                <label>Parent Category</label>
                <select name="parent_cid">
                    <option value="0">No Parent</option>
                    <?php foreach($categories as $c): ?>
                        <?php $sel = ($c['cid'] == $category['parent_cid']) ? 'selected="selected"' : ''; ?>
                        <option value="<?php echo $c['cid']; ?>" <?php echo $sel; ?>>
                            <?php echo $c['name']; ?> 
                        </option>
                    <?php endforeach; ?>
                </select>
            </li>
            <li class="text medium">
                <label>Category Name</label>
                <input type="text" name="name" value="<?php echo $category['name']; ?>" />
            </li>
            <li class="text medium">
                <label>Category Slug</label>
                <input type="text" name="slug" value="<?php echo $category['slug']; ?>" />
            </li>
            <li class="buttons">
                <input type="submit" value="Update" />
            </li>
        </ul>
    </form>
</div>
