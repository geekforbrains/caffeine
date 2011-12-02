<h1>Cart</h1>

<form method="post" action="<?php l('store/cart'); ?>">
    <table border="1">
        <tr>
            <th colspan="2">Product</th>
            <th>Options</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
        <?php if($products): ?>
            <?php foreach($products as $k => $p): ?>
                <tr>
                    <td>
                        <a href="<?php l('store/product/%s', $p['slug']); ?>">
                            <?php if($p['images']): ?>
                                <img src="<?php l('media/image/%d/0/75/75', $p['images'][0]['media_cid']); ?>" />
                            <?php else: ?>
                                <img src="<?php l('media/image/0/75/75'); ?>" />
                            <?php endif; ?>
                        </a>
                    </td>
                    <td>
                        <a href="<?php l('store/product/%s', $p['slug']); ?>">
                            <?php echo $p['title']; ?></td>
                        </a>
                    </td>
                    <td>
                        <?php if(isset($p['option_values'])): ?>
                            <?php foreach($p['option_values'] as $o): ?>
                                <?php echo $o['type']; ?>: <?php echo $o['value']; ?><br />
                            <?php endforeach; ?>
                        <?php else: ?>
                            <em>N/A</em>
                        <?php endif; ?>
                    </td>
                    <td><input type="text" name="qty[<?php echo $k; ?>]" size="2" value="<?php echo $p['qty']; ?>" /></td>
                    <td><?php echo $symbol . number_format($p['price'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
                <tr>
                    <td colspan="4" align="right">Subtotal</td>
                    <td><?php echo $symbol . $subtotal . ' ' . $currency; ?></td>
        <?php else: ?>
            <tr><td colspan="4"><em>Cart is empty.</em></td></tr>
        <?php endif; ?>

        <tr>
            <td colspan="3">
                <input type="submit" name="update_cart" value="Update Cart" />
                <input type="submit" name="empty_cart" value="Empty Cart" />
            </td>
            <td colspan="2">
                <a href="<?php l('store/express-checkout'); ?>">
                    <img src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" align="left" style="margin-right:7px;">
                </a>
                <a href="<?php l('store/checkout'); ?>">Regular Checkout</a>
            </td>
        </tr>
    </table>
</form>
