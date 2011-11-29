<?php View::load('Store', 'admin/sidenav'); ?>

<div class="area right">
    <h2>General Settings</h2>
    <form method="post" action="<?php l('admin/store/settings/general'); ?>">
        <ul>
            <li class="text medium">
                <label>Currency</label>
                <input type="text" name="currency" value="<?php echo $currency; ?>" />
            </li>
            <li class="text medium">
                <label>Currency Symbol</label>
                <input type="text" name="symbol" value="<?php echo $symbol; ?>" />
            </li>
            <li class="text medium">
                <label>Tax Percentage</label>
                <input type="text" name="tax" value="<?php echo $tax; ?>" />
            </li>
            <li class="buttons">
                <input type="submit" value="Update" />
            </li>
        </ul>
    </form>
</div>
