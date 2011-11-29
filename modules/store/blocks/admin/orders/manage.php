<div class="area">
    <h2><?php echo ucwords($sort); ?> Orders</h2>
    <div class="fixed">
        <form method="post" action="<?php l('admin/store/orders'); ?>">
            Sort by Status
            <select name="sort">
                <option value="all" <?php if($sort == 'all') echo 'selected="selected"'; ?>>All</option>
                <option value="new" <?php if($sort == 'new') echo 'selected="selected"'; ?>>New</option>
                <option value="shipped" <?php if($sort == 'shipped') echo 'selected="selected"'; ?>>Shipped</option>
                <option value="cancelled" <?php if($sort == 'cancelled') echo 'selected="selected"'; ?>>Cancelled</option>
            </select>
            <input type="submit" value="Sort" />
        </form>
    </div>
    <table class="stripe" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <th>Order Number</th>
        <th>Name</th>
            <th>Total</th>
            <th>Status</th>
            <th style="text-align: right">Date</th>
        </tr>
        <?php if($orders): ?>
            <?php foreach($orders as $o): ?>
                <tr>
                    <td>
                        <a href="<?php l('admin/store/orders/details/%d', $o['cid']); ?>">
                            #<?php echo $o['cid']; ?>
                        </a>
                    </td>
                    <td>
                        <?php echo $o['first_name']; ?>
                        <?php echo $o['last_name']; ?>
                    </td>
                    <td><?php echo $symbol . number_format($o['total'], 2) . ' ' . $currency; ?></td>
                    <td><?php echo ucwords($o['status']); ?></td>
                    <td align="right"><?php echo date('M jS, Y', $o['created']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5"><em>No orders</em></td></tr>
        <?php endif; ?>
    </table>
</div>
