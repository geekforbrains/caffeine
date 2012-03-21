<? View::insert('includes/header'); ?>

<!-- start content -->
<div class="content container_12">
    <div class="grid_4 prefix_4 spacer">
        <h1>Install</h1>
        <?= Html::form()->open(null, 'post', false, array('id' => 'install', 'name' => 'install')); ?>
            <ul style="margin-top: 18px;">
                <li class="text full">
                    <label>Email</label>
                    <input type="text" name="email" />
                </li>
                <li class="text full">
                    <label>Password</label>
                    <input type="password" name="password" />
                </li>
                <li class="text full">
                    <label>Confirm Password</label>
                    <input type="password" name="conf_password" />
                </li>
                <li class="buttons">
                    <a class="btn blue" href="javascript:document.install.submit();">Install</a>
                </li>
            </ul>
        <?= Html::form()->close(); ?>
    </div>
</div>
<!-- end content -->

<? View::insert('includes/footer'); ?>
