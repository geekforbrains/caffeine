<? View::insert('includes/header'); ?>

<!-- start content -->
<div class="content container_12">
    <div class="grid_4 prefix_4 spacer">
        <h1>Login</h1>
        <?= Html::form()->open(null, 'post', false, array('id' => 'login', 'name' => 'login')); ?>
            <ul style="margin-top: 18px;">
                <li class="text full">
                    <label>Email</label>
                    <input type="text" name="email" />
                </li>
                <li class="text full">
                    <label>Password</label>
                    <input type="password" name="password" />
                </li>
                <li class="buttons">
                    <a class="btn blue" href="javascript:document.login.submit();">Login</a>&nbsp;
                    <a class="btn gray" href="javascript:alert('This doesnt work yet');">Reset Password</a>
                </li>
            </ul>
        <?= Html::form()->close(); ?>
    </div>
</div>
<!-- end content -->

<? View::insert('includes/footer'); ?>
