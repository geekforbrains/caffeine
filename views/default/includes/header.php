<html>
<head>
	<title>Caffeine</title>
	<?php View::get_css(); ?>
	<?php View::get_js(); ?>
</head>
<body>

<div id="page">
	<div id="header">
		<h1>Caffeine <?php echo CAFFEINE_VERSION; ?></h1>
	</div>
	
	<div id="sidebar">
		<?php echo Menu::build(); ?>
	</div>
