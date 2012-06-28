<? View::insert('includes/header'); ?>

    <div class="row-fluid">
        <div class="span4">&nbsp;</div>
        <div class="span4">
            <div class="page-header">
                <h1>Install</h1>
            </div>
        </div>
        <div class="span4">&nbsp;</div>
    </div>

    <div class="row-fluid">
        <div class="span4">&nbsp;</div>
        <div class="span4">

            <form class="well" method="post" action="<?= _current(); ?>">
                <div class="<?= _e('email')->getClass('control-group'); ?>">
                    <input type="text" name="email" class="span12" placeholder="Email" value="<?= _p('email'); ?>" />
                </div>

                <div class="<?= _e('pass')->getClass('control-group'); ?>">
                    <input type="password" name="pass" class="span12" placeholder="Password" />
                </div>

                <div class="<?= _e('pass_conf')->getClass('control-group'); ?>">
                    <input type="password" name="pass_conf" class="span12" placeholder="Confirm Password" />
                </div>

                <input class="btn" type="submit" name="install" value="Install" />
            </form>

        </div>
        <div class="span4">&nbsp;</div>
    </div>

<? View::insert('includes/footer'); ?>
