<?php View::insert('includes/header'); ?>

    <div class="grid_3">&nbsp;</div>

    <div class="grid_6">
        <div class="box">
            <h2>Create Admin</h2>
            <div class="block">
                <?php echo Html::form()->open(); ?>
                    <fieldset class="login">
                        <p>
                            <label>Admin Email: </label>
                            <input type="text" name="email" />
                            <?php echo Validate::error('email'); ?>
                        </p>
                        <p>
                            <label>Admin Password: </label>
                            <input type="password" name="password" />
                            <?php echo Validate::error('password'); ?>
                        </p>
                        <p>
                            <label>Confirm Password: </label>
                            <input type="password" name="conf_password" />
                            <?php echo Validate::error('conf_password'); ?>
                        </p>
                        <input class="login button" type="submit" value="Install" />
                    </fieldset>
                <?php echo Html::form()->close(); ?>
            </div>
        </div>
    </div>

    <div class="grid_3">&nbsp;</div>

<?php View::insert('includes/footer'); ?>
