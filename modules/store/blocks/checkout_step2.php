<h1>Checkout - Step 2</h1>

Subtotal: <?php echo $symbol . number_format($order['subtotal'], 2); ?><br />
Shipping: <?php echo $symbol . number_format($order['shipping'], 2); ?><br />
Tax: <?php echo $symbol . number_format($order['tax'], 2); ?><br />
Total: <?php echo $symbol . number_format($order['total'], 2) . ' ' . $currency; ?>

<h2>Payment Information</h2>
<form method="post" action="<?php l('store/checkout/step2'); ?>">
    <input type="hidden" name="total" value="<?php echo $order['total']; ?>" />

    Credit Cart Type
    <select name="cc_type">
        <option value="Visa">Visa</option>
        <option value="Mastercard">Mastercard</option>
    </select>
    <br />
    
    Credit Card Number
    <input type="text" name="cc_num" />
    <br />

    Credit Card Expiry (MMYYYY)
    <select name="cc_exp_month">
        <?php for($i = 1; $i <= 12; $i++): ?>
            <option value="<?php echo $i; ?>">
                <?php echo $i; ?>
            </option>
        <?php endfor; ?>
    </select>
    <select name="cc_exp_year">
        <?php $year = date('Y'); ?>
        <?php for($i = $year; $i <= $year + 3; $i++): ?>
            <option value="<?php echo $i; ?>">
                <?php echo $i; ?>
            </option>
        <?php endfor; ?>
    </select>
    
    <input type="text" name="cc_exp_month" />
    <br />

    Credit Card CVV2
    <input type="text" name="cc_cvv2" />
    <br />

    <hr />

    First Name
    <input type="text" name="first_name" />
    <br />

    Last Name
    <input type="text" name="last_name" />
    <br />

    Address
    <input type="text" name="address" />
    <br />

    City
    <input type="text" name="city" />
    <br />

    State/Province
    <input type="text" name="state" />
    <br />

    Zip/Postal Code
    <input type="text" name="zip" />
    <br />

    Country
    <select name="country">
        <?php foreach(String::$countries as $code => $country): ?>
            <option value="<?php $code; ?>">
                <?php echo $country; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="submit" value="Continue" />
</form>
