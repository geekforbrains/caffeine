<?php View::load('Store', 'admin/sidenav'); ?>

<div class="area right">
    <h2>PayPal Pro Settings</h2>
    <form>
        <ul>
            <li class="text medium">
                <label>Username</label>
                <input type="text" name="username" />
            </li>
            <li class="text medium">
                <label>Password</label>
                <input type="text" name="password" />
            </li>
            <li class="text medium">
                <label>Signature</label>
                <input type="text" name="signature" />
            </li>
            <li class="buttons">
                <input type="submit" value="Update" />
            </li>
        </ul>
    </form>
</div>
