<div class="area">
    <div class="area left half">
        <h2>Order #<?php echo $order['cid']; ?></h2>
        <table class="stripe" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <th width="125">Name</th>
                <td>
                    <?php echo $customer['first_name']; ?>
                    <?php echo $customer['last_name']; ?>
                </td>
            </tr>
			<tr>
				<th>Email</th>
				<td>
					<?php echo $customer['email']; ?>				
				</td>
			</tr>
            <tr>
                <th>Address</th>
                <td><?php echo $customer['address1']; ?></td>
            </tr>
            <tr>
                <th>Suite/Apt #</th>
                <td>
                    <?php if(strlen($customer['address2'])): ?>
                        <?php echo $customer['address2']; ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            <tr>
                <th>City</th>
                <td><?php echo $customer['city']; ?></td>
            </tr>
            <tr>
                <th>State/Province</th>
                <td><?php echo $customer['state']; ?></td>
            </tr>
            <tr>
                <th>Zip/Postal</th>
                <td><?php echo $customer['zip']; ?></td>
            </tr>
            <tr>
                <th>Country</th>
                <td><?php echo $customer['country']; ?></td>
            </tr>
        </table>
    </div>

    <div class="area right half">
        <h2>Payment Info</h2>
        <table class="stripe" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <th width="125">Subtotal</th>
                <td><?php echo $symbol . number_format($order['subtotal'], 2); ?></td>
            </tr>
            <tr>
                <th>Shipping</th>
                <td><?php echo $symbol . number_format($order['shipping'], 2); ?></td>
            </tr>
            <tr>
                <th>Tax</th>
                <td><?php echo $symbol . number_format($order['tax'], 2); ?></td>
            </tr>
            <tr>
                <th>Total</th>
                <td>
                    <?php echo $symbol . number_format($order['total'], 2); ?>
                    <?php echo $currency; ?>
                </td>
            </tr>
            <tr>
                <th>Order Status</th>
                <td>
                    <form method="post" action="<?php echo Router::current_url(); ?>">
                        <select name="status">
                            <option value="new" <?php if($order['status'] == 'new') echo 'selected="selected"'; ?>>New</option>
                            <option value="shipped" <?php if($order['status'] == 'shipped') echo 'selected="selected"'; ?>>Shipped</option>
                            <option value="cancelled" <?php if($order['status'] == 'cancelled') echo 'selected="selected"'; ?>>Cancelled</option>
                        </select>
                        <input type="submit" name="update_status" value="Update" />
                    </form>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="area">
    <h2>Products</h2>
    <table class="stripe" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <th colspan="2">Title</th>
            <th>Options</th>
            <th>SKU</th>
            <th>Price</th>
            <th>Quantity</th>
            <th style="text-align: right">Total</th>
        </tr>   
        <?php foreach($products as $p): ?>
            <tr valign="top">
                <td width="75">
                    <a href="<?php l('admin/store/products/edit/%d', $p['cid']); ?>">
                        <?php if($p['images']): ?>
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
                <td>
                    <?php if($p['options']): ?>
                        <?php foreach($p['options'] as $o): ?>
                            <strong><?php echo $o['type']; ?>:</strong>
                            <?php echo $o['value']; ?><br />
                        <?php endforeach; ?>
                    <?php else: ?>
                        <em>N/A</em>
                    <?php endif; ?>
                </td>
                <td><?php echo $p['sku']; ?></td>
                <td><?php echo $symbol . $p['price']; ?></td>
                <td><?php echo $p['qty']; ?></td>
                <td align="right"><strong><?php echo $symbol . number_format($p['price'] * $p['qty'], 2); ?></strong></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
