<html>
<head>
	<base href="<?php echo View::theme_url(); ?>/" />
	<title>Media Dialog</title>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
	<script type="text/javascript" src="js/tiny_mce/tiny_mce_popup.js"></script>
	<script type="text/javascript">
		var mediaURL = '<?php echo Router::base(); ?>/media/image/';
		var currentID = <?php echo $images[0]['cid']; ?>;
		var defaultSize = 500; // Default width when resetting
		var rotation = 0; // Set starting rotation
	</script>
	<script type="text/javascript" src="js/admin_dialog.js"></script>
<body>
	<?php echo Message::display(); ?>

	<form method="post" action="<?php l('admin/media/dialog/%s', $type); ?>" enctype="multipart/form-data">
		<input type="file" name="media_file" />
		<input type="submit" />
	</form>

	<?php View::content(); ?>
</body>
</html>
