<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <base href="http://localhost/dev/core/admin/views/" />
    <title>Control Panel</title>
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.7.2.custom.css" />
    <script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.7.2.custom.min.js"></script>
    <script type="text/javascript" src="js/tiny_mce/tiny_mce.js"></script>
    <script type="text/javascript" src="js/admin.js"></script>
</head>
<body>
    <div id="header">
        <div class="center">
            <h1>Control Panel</h1>
            <span>
                Logged in as <a href="#">Administrator</a> &nbsp;|&nbsp;
                <a href="#">Logout</a>
            </span>
            <div class="tabs">
                <?php echo Menu::build(0, 'admin', array('attributes' => array('class' => 'left'))); ?>
            </div>
        </div>
        <div class="sub_tabs">
            <div class="center">
                <?php echo Menu::build(0, 'admin/%s', array('attributes' => array('class' => 'left'))); ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>


    <?php /*
    <div id="messages">
        <div class="notify">Notify</div>
        <div class="error">Error</div>
    </div>
    */ ?>


    <div id="content">