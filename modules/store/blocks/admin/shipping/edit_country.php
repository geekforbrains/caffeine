<div class="area">
    <h2>Edit Country</h2>
    <form method="post" action="<?php echo Router::current_url(); ?>">
        <ul>
            <li class="text medium">
                <label>Country</label>
                <input type="text" name="name" value="<?php echo $country['name']; ?>" />
                <?php Validate::error('name'); ?>
            </li>
            <li class="text medium">
                <label>Country Taxes <em>(overrides other tax settings)</em></label>
                <input type="text" name="tax" value="<?php echo $country['tax']; ?>" />
            </li>
            <li class="buttons">
                <input type="submit" name="edit_country" value="Update Country" />
            </li>
        </ul>
    </form>
</div>

<div class="area">
    <h2>Country Shipping Sizes</h2>
    <div class="fixed">
        <form method="post" action="<?php echo Router::current_url(); ?>">
            <select name="size_cid">
                <?php foreach($sizes as $s): ?>
                    <option value="<?php echo $s['cid']; ?>">
                        <?php echo $s['size']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="submit" name="add_size" value="Add" />
        </form>
    </div>

    <form method="post" action="<?php echo Router::current_url(); ?>">
        <table class="stripe" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <th>Size</th>
                <th colspan="2">Price</th>
            </tr>
            <?php if($country_sizes): ?>
                <?php foreach($country_sizes as $cs): ?>
                    <tr>
                        <td><?php echo $cs['size']; ?></td>
                        <td>
                            <input type="text" name="cid<?php echo $cs['shipping_size_cid']; ?>" 
                                value="<?php echo number_format($cs['price'], 2); ?>" />
                        </td>
                        <td align="right">
                            <a href="<?php l('admin/store/shipping/edit-country/%d/delete-size/%d', 
                                $country['cid'], $cs['shipping_size_cid']); ?>">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3"><em>No sizes</em></td></tr>
            <?php endif; ?>
        </table>

        <div class="buttons">
            <input type="submit" name="update_sizes" value="Update Sizes" />
        </div>
    </form>
</div>
