<?php View::insert('includes/header'); ?>

    <div class="grid_3">&nbsp;</div>

    <div class="grid_6">
        <div class="box">
            <h2>Login</h2>
            <div class="block">
                <?php echo Html::form()->open(); ?>
                    <fieldset class="login">
                        <p>
                            <label>Username: </label>
                            <input type="text" name="email" />
                        </p>
                        <p>
                            <label>Password: </label>
                            <input type="password" name="password" />
                        </p>
                        <input class="login button" type="submit" value="Login" />
                    </fieldset>
                <?php echo Html::form()->close(); ?>
            </div>
        </div>
    </div>

    <div class="grid_3">&nbsp;</div>

<?php View::insert('includes/footer'); ?>
