<?php View::load('Portfolio', 'admin/sidenav'); ?>

<div class="area right">
    <h2>Manage Items</h2>
    <table class="stripe" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <th>Name</th>
            <th colspan="2">Category</th>
        </tr>

        <?php if($items): ?>
            <?php foreach($items as $i): ?>
                <tr>
                    <td>
                        <a href="<?php l('admin/portfolio/items/edit/%d', $i['cid']); ?>">
                            <?php echo $i['name']; ?>
                        </a>
                    </td>
                    <td><?php echo $i['category']; ?></td>
                    <td align="right">
                        <a href="<?php l('admin/portfolio/items/delete/%d', $i['cid']); ?>">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3"><em>No items.</em></td></tr>
        <?php endif; ?>
    </table>
</div>
