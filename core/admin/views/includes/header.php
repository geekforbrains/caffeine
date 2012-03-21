<!DOCTYPE html>
<html>
<head>
    <base href="<?php echo View::getBaseHref(); ?>" />
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

    <title><?php echo View::getTitle('Control Panel', null, ' - Control Panel'); ?></title>

    <link rel="stylesheet" type="text/css" href="css/reset.css" />
    <link rel="stylesheet" type="text/css" href="css/grid.css" />
    <link rel="stylesheet" type="text/css" href="css/main.css" />
    <link rel="stylesheet" type="text/css" href="plugins/chosen/chosen.css" />
	<link rel="stylesheet" type="text/css" href="plugins/smoothness/jquery-ui-1.8.17.custom.css" />

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
    <script type="text/javascript" src="plugins/tiny_mce/jquery.tinymce.js"></script>
    <script type="text/javascript" src="plugins/chosen/chosen.jquery.js"></script>

    <script type="text/javascript">
        var baseHref = '<?php echo View::getBaseHref(); ?>'; // Used by plugins to get relative paths to scripts
    </script>

    <script type="text/javascript" src="js/admin.js"></script>
</head>
<body>


<!-- jquey modal -->
<div id="modal" title="Hello World">
   <p>This is a modal</p> 
</div>


<!-- start header -->
<div class="header">
    <div class="container_12">
        <h1 class="grid_12">Control Panel</h1>

        <?php if(!User::current()->isAnonymous()): ?>
            <div class="user_box">
                Logged in as
                <?php Html::a(User::current()->email, 'admin/user/edit/' . User::current()->id); ?>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <?php Html::a('Logout', 'admin/logout'); ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="clear">&nbsp;</div>

    <div class="tab_bar">
        <div class="container_12">
            <div class="grid_12">
                <?php echo Menu::build(0, 'admin', array('attributes' => array('class' => 'tabs'))); ?>
            </div>
            <div class="clear">&nbsp;</div>
        </div>
    </div>

    <div class="crumbs">
        <div class="container_12">
            <div class="grid_12">
                <!--
                <a href="#">Cookie</a><span class="splitter">&rarr;</span>
                <a href="#">Crumb</a><span class="splitter">&rarr;</span>
                Trail
                -->
            </div>
            <div class="clear">&nbsp;</div>
        </div>
    </div>
</div>
<!-- end header -->


<!-- start messages -->
<?php if($messages = Message::get()): ?>
    <div class="container_12">
        <div class="grid_12 messages">
            <?php foreach($messages as $type => $typeMessages): ?>
                <?php foreach($typeMessages as $message): ?>
                    <p class="<?php echo $type; ?>"><?php echo $message; ?></p>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
<!-- end messages -->
