<div class="area">
    <div class="area">
        <h2>Create Size</h2>
        <form method="post" action="<?php echo Router::current_url(); ?>">
            <ul>
                <li class="text medium">
                    <label>Size</label>
                    <input type="text" name="size" />
                    <?php Validate::error('size'); ?>
                </li>
                <li class="text medium">
                    <label>Price</label>
                    <input type="text" name="price" />
                    <?php Validate::error('price'); ?>
                </li>
                <li class="buttons">
                    <input type="submit" value="Add" />
                </li>
            </ul>
        </form>
    </div>

    <div class="area">
        <h2>Manage Sizes</h2>
        <table class="stripe" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <th>Size</th>
                <th colspan="2">Price</th>
            </tr>
            <?php if($sizes): ?>
                <?php foreach($sizes as $s): ?>
                    <tr>
                        <td><a href="<?php l('admin/store/shipping/sizes/edit/%d', $s['cid']); ?>"><?php echo $s['size']; ?></a></td>
                        <td>$<?php echo number_format($s['price'], 2); ?></td>
                        <td align="right"><a href="<?php l('admin/store/shipping/sizes/delete/%d', $s['cid']); ?>">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3"><em>No sizes</em></td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>
