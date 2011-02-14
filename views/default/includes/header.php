<html>
<head>
	<base href="<?php echo View::theme_url(); ?>/" />
	<title><?php echo View::get_title('Caffeine', 'Caffeine | '); ?></title>
	<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>

<div id="page">
	<div id="header">
		<h1>Caffeine <?php echo CAFFEINE_VERSION; ?></h1>
	</div>
	
	<div id="sidebar">
		<?php echo Menu::build(); ?>
	</div>
