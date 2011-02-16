<html>
<head>
	<base href="<?php echo View::theme_url(); ?>/" />
	<title>Media Dialog</title>
	<script type="text/javascript" src="js/tiny_mce/tiny_mce_popup.js"></script>
	<script type="text/javascript">
		function inject(URL)
		{
			var win = tinyMCEPopup.getWindowArg("window");

			// insert information now
			win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = URL;

			// are we an image browser
			if (typeof(win.ImageDialog) != "undefined")
			{
				// we are, so update image dimensions and preview if necessary
				if (win.ImageDialog.getImageData) win.ImageDialog.getImageData();
				if (win.ImageDialog.showPreviewImage) win.ImageDialog.showPreviewImage(URL);
			}

			// close popup window
			tinyMCEPopup.close();
		}
	</script>
<body>
	<?php View::content(); ?>
</body>
</html>
