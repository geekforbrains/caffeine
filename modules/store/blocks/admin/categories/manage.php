<div class="area">
    <h2>Create Category</h2>
    <form method="post" action="<?php l('admin/store/categories'); ?>">
        <ul>
            <li class="select medium">
                <label>Parent Category</label>
                <select name="parent_cid">
                    <option value="0">No Parent</option>
                    <?php foreach($categories as $c): ?>
                        <option value="<?php echo $c['cid']; ?>">
                            <?php echo $c['indent'] . $c['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </li>
            <li class="text medium">
                <label>Category Name</label>
                <input type="text" name="name" />
                <?php Validate::error('name'); ?>
            </li>
            <li class="buttons">
                <input type="submit" value="Add" />
            </li>
        </ul>
    </form>
</div>

<div class="area">
    <h2>Manage Categories</h2>
    <table class="stripe" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <th colspan="2">Category</th>
        </tr>
        <?php if($categories): ?>
            <?php foreach($categories as $c): ?>
                <tr>
                    <td>
                        <a href="<?php l('admin/store/categories/edit/%d', $c['cid']); ?>">
                            <?php echo $c['indent']; ?>
                            <?php echo $c['name']; ?>
                        </a>
                    </td>
                    <td align="right">
                        <a href="<?php l('admin/store/categories/delete/%d', $c['cid']); ?>">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="2"><em>No categories</em></td></tr>
        <?php endif; ?>
    </table>
</div>
