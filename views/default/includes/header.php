<html>
<head>
	<base href="<?php echo View::theme_url(); ?>/" />
	<?php SEO::meta(); ?>
	<title><?php echo SEO::title(); ?></title>
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
