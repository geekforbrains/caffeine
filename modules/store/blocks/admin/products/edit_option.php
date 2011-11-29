<div class="area left short">
    <h3>Navigation</h3>
    <?php echo Menu::build('admin/store/%s', 0, array(
        'before' => array(
            array(
                'title' => '&laquo; Back to Product',
                'path' => 'admin/store/products/edit/' . $product_cid
            )
        )
    )); ?>
</div>

<div class="area right">
    <div class="area left half">
        <h2>Edit Option Type</h2>
        <form method="post" action="<?php echo Router::current_url(); ?>">
            <ul>
                <li class="text medium">
                    <label>Option</label>
                    <input type="text" name="option" value="<?php echo $option['name']; ?>" />
                </li>
                <li class="buttons">
                    <input type="submit" name="update_option" value="Update" />
                </li>
            </ul>
        </form>
    </div>

    <div class="area right half">
        <h2>Add Option Value</h2>
        <form method="post" action="<?php echo Router::current_url(); ?>">
            <ul>
                <li class="text medium">
                    <label>Option Value</label>
                    <input type="text" name="value" />
                </li>
                <li class="text medium">
                    <label>Option Price <em><small>(Overrides Product Price)</small></em></label>
                    <input type="text" name="price" />
                </li>
                <li class="text medium">
                    <label>Option SKU <em><small>(Overrides Product SKU)</small></em></label>
                    <input type="text" name="sku" />
                </li>
                <li class="button">
                    <input type="submit" name="add_value" value="Add Value" />
                </li>
            </ul>
        </form>
    </div>

    <div class="clear"></div>

    <div class="area">
        <h2>Manage Values</h2>
        <table class="stripe" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <th>Value</th>
                <th>Override Price</th>
                <th colspan="2">Override SKU</th>
            </tr>
            <?php if($values): ?>
                <?php foreach($values as $v): ?>
                    <tr>
                        <td><?php echo $v['value']; ?></td>
                        <td>
                            <?php if($v['price'] > 0): ?>
                                <?php echo $symbol . $v['price']; ?>
                            <?php else: ?>
                                <em>N/A</em>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if(strlen($v['sku'])): ?>
                                <?php echo $v['sku']; ?>
                            <?php else: ?>
                                <em>N/A</em>
                            <?php endif; ?>
                        </td>
                        <td align="right">
                            <a href="<?php l('admin/store/products/edit/%d/edit-option/%d/delete-value/%d',
                                $product_cid, $option['cid'], $v['cid']); ?>">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4"><em>No values</em></td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>
