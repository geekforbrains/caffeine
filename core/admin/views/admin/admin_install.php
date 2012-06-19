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
            <form class="well">
                <input type="text" name="email" class="span12" placeholder="Email" />
                <input type="password" name="pass" class="span12" placeholder="Password" />
                <input type="password" name="pass_conf" class="span12" placeholder="Confirm Password" />

                <button type="submit" class="btn">Install</button>
            </form>
        </div>
        <div class="span4">&nbsp;</div>
    </div>

<? View::insert('includes/footer'); ?>
