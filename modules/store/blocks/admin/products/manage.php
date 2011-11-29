<?php View::load('Store', 'admin/sidenav'); ?>

<div class="area right">
    <div class="area">
        <h2>Featured Products</h2>
        <table class="stripe" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <th colspan="2">Title</th>
                <th colspan="2">Price</th>
            </tr>
            <?php if($featured): ?>
                <?php foreach($featured as $p): ?>
                    <tr valign="top">
                        <td width="75">
                            <a href="<?php l('admin/store/products/edit/%d', $p['cid']); ?>">
                                <?php if(isset($p['images'][0])): ?>
                                    <img src="<?php l('media/image/%d/0/75/75', $p['images'][0]['media_cid']); ?>" />
                                <?php else: ?>
                                    <img src="<?php l('media/image/0/75/75'); ?>" />
                                <?php endif; ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?php l('admin/store/products/edit/%d', $p['cid']); ?>">
                                <?php echo $p['title']; ?>
                            </a>
                        </td>
                        <td>$<?php echo number_format($p['price'], 2); ?></td>
                        <td align="right">
                            <a href="<?php l('admin/store/products/delete/%d', $p['cid']); ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4"><em>No products.</em></td></tr>
            <?php endif; ?>
        </table>
    </div>

    <div class="area">
        <h2>Manage Products</h2>

        <table class="stripe" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <th colspan="2">Title</th>
                <th colspan="2">Price</th>
            </tr>
            <?php if($products): ?>
                <?php foreach($products as $p): ?>
                    <tr valign="top">
                        <td width="75">
                            <a href="<?php l('admin/store/products/edit/%d', $p['cid']); ?>">
                                <?php if(isset($p['images'][0])): ?>
                                    <img src="<?php l('media/image/%d/0/75/75', $p['images'][0]['media_cid']); ?>" />
                                <?php else: ?>
                                    <img src="<?php l('media/image/0/75/75'); ?>" />
                                <?php endif; ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?php l('admin/store/products/edit/%d', $p['cid']); ?>">
                                <?php echo $p['title']; ?>
                            </a>
                        </td>
                        <td>$<?php echo number_format($p['price'], 2); ?></td>
                        <td align="right">
                            <a href="<?php l('admin/store/products/delete/%d', $p['cid']); ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4"><em>No products.</em></td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>
