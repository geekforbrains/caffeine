<h1>Checkout - Step 1</h1>
<h2>Shipping Information</h2>

<form method="post" action="<?php l('store/checkout'); ?>">
    First Name
    <input type="text" name="first_name" />
    <?php Validate::error('first_name'); ?>
    <br />

    Last Name
    <input type="text" name="last_name" />
    <?php Validate::error('last_name'); ?>
    <br />

    Email
    <input type="text" name="email" />
    <?php Validate::error('email'); ?>
    <br />

    Address
    <input type="text" name="address1" />
    <?php Validate::error('address1'); ?>
    <br />

    Apt/Building #
    <input type="text" name="address2" />
    <br />

    City
    <input type="text" name="city" />
    <?php Validate::error('city'); ?>
    <br />

    State/Province
    <select name="shipping_state_cid">
        <option value="">Choose One</option>
        <?php foreach($states as $country => $cs): ?>
            <optgroup label="<?php echo $country; ?>">
                <?php foreach($cs as $s): ?>
                    <option value="<?php echo $s['cid']; ?>">
                        <?php echo $s['name']; ?>
                    </option>
                <?php endforeach; ?>
            </optgroup>
        <?php endforeach; ?>
    </select>
    <?php Validate::error('shipping_state_cid'); ?>
    <br />

    Zip/Postal Code
    <input type="text" name="zip" />
    <?php Validate::error('zip'); ?>
    <br />

    <input type="submit" value="Continue" />
</form>
