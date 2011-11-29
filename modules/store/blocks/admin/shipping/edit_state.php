<div class="area">
    <h2>Edit State/Province</h2>
    <form method="post" action="<?php echo Router::current_url(); ?>">
        <ul>
            <li class="select medium">
                <label>Country</label>
                <select name="shipping_country_cid">
                    <?php foreach($countries as $c): ?>
                        <?php $sel = ($c['cid'] == $state['shipping_country_cid']) ? 'selected="selected"' : ''; ?>
                        <option value="<?php echo $c['cid']; ?>" <?php echo $sel; ?>>
                            <?php echo $c['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <li class="text medium">
                <label>State/Province</label>
                <input type="text" name="name" value="<?php echo $state['name']; ?>" />
            </li>
            <li class="text medium">
                <label>State Taxes <em>(overrides other tax settings)</em></label>
                <input type="text" name="tax" value="<?php echo $state['tax']; ?>" />
            </li>
            <li class="buttons">
                <input type="submit" name="update_state" value="Update State" />
            </li>
        </ul>
    </form>
</div>

<div class="area">
    <h2>State Shipping Sizes</h2>
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
            <?php if($state_sizes): ?>
                <?php foreach($state_sizes as $ss): ?>
                    <tr>
                        <td><?php echo $ss['size']; ?></td>
                        <td><input type="text" name="cid<?php echo $ss['shipping_size_cid']; ?>" value="<?php echo number_format($ss['price'], 2); ?>" /></td>
                        <td align="right"><a href="#">Delete</a></td>
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
