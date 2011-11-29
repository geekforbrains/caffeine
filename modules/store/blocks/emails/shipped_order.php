<html>
<head>
    <title>Shipped Order</title>
</head>
<body>

<p>THIS IS AN EXAMPLE FILE, YOU SHOULD MODIFY IT TO SUIT YOUR NEEDS</p>

<p>Your order has been shipped!</p>

<table>
    <?php foreach($products as $p): ?>
        <tr>
            <td><?php echo $p['title']; ?></td>
            <td><?php echo $p['qty']; ?></td>
            <td>$<?php echo number_format($p['price'], 2); ?></td>
        </tr>
    <?php endforeach; ?>

    <tr>
        <td align="right" colspan="2">Subtotal</td>
        <td>$<?php echo number_format($order['subtotal'], 2); ?></td>
    </tr>
    <tr>
        <td align="right" colspan="2">Shipping</td>
        <td>$<?php echo number_format($order['shipping'], 2); ?></td>
    </tr>
    <tr>
        <td align="right" colspan="2">Taxes</td>
        <td>$<?php echo number_format($order['tax'], 2); ?></td>
    </tr>
    <tr>
        <td align="right" colspan="2">Total</td>
        <td><strong>$<?php echo number_format($order['total'], 2); ?></strong></td>
    </tr>
</table>

</body>
</html>
