<html>
<head>
	<base href="<?php echo View::theme_url(); ?>/" />
	<?php /*<title><?php echo View::get_title('Caffeine', 'Caffeine | '); ?></title>*/ ?>
	<title><?php echo SEO::title('Default Title', 'Caffeine | '); ?></title>
	<?php echo SEO::meta('author', 'Default Author'); ?>
	<?php echo SEO::meta('description', 'Default Description'); ?>
	<?php echo SEO::meta('keywords', 'Default Keywords'); ?>
	<?php echo SEO::meta('robots', 'Default Robots'); ?>
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
