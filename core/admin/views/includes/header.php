<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
        <base href="<?php echo View::getBaseHref(); ?>" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Control Panel</title>
		<link rel="stylesheet" type="text/css" href="css/reset.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/text.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/grid.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/layout.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/nav.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="css/custom.css" media="screen" />
		<!--[if IE 6]><link rel="stylesheet" type="text/css" href="css/ie6.css" media="screen" /><![endif]-->
		<!--[if IE 7]><link rel="stylesheet" type="text/css" href="css/ie.css" media="screen" /><![endif]-->
	</head>
	<body>
		<div class="container_12">
			<div class="grid_12">
				<h1 id="branding">
					<a href="#"><?php echo Config::get('admin.title'); ?></a>
				</h1>
			</div>
			<div class="clear"></div>

			<div class="grid_12">
                <?php echo Menu::build(1, 'admin', array('attributes' => array('class' => 'nav main'))); ?>
			</div>
			<div class="clear"></div>

            <?php if($messages = Message::get()): ?>
                <div class="grid_12 messages">
                    <div class="block">
                        <?php foreach($messages as $type => $typeMessages): ?>
                            <?php foreach($typeMessages as $message): ?>
                                <p class="<?php echo $type; ?>"><?php echo $message; ?></p>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- spacer -->
            <div class="grid_12">&nbsp;</div>
