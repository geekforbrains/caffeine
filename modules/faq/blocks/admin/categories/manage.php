<div class="area left short">
    <h3>Navigation</h2>
    <?php echo Menu::build('admin/faq/categories', 0); ?>
</div>

<div class="area right">
    <h2>Manage Categories</h2>
    <table class="stripe" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <th colspan="2">Category</th>
        </tr>

        <?php if($categories): ?>
            <?php foreach($categories as $c): ?>
                <tr>
                    <td>
                        <a href="<?php l('admin/faq/categories/edit/%d', $c['cid']); ?>">
                            <?php echo $c['name']; ?>
                        </a>
                    </td>
                    <td align="right">
                        <a href="<?php l('admin/faq/categories/delete/%d', $c['cid']); ?>">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="2"><em>No categories.</em></td></tr>
        <?php endif; ?>
    </table>
</div>
