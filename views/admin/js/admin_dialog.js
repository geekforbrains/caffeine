function update(url) {
	$('#main_href').attr('href', url);
	$('#main_img').attr('src', url);
}

$(document).ready(function() {

	// When thumb is clicked, set main window and source URL to use
	// new URL based on thumbs a href
	$('.img_thumb').click(function(e) {
		e.preventDefault();
		currentID = $(this).attr('name');
		update($(this).attr('href'));
		rotation = 0;
	});

	// Creates a new URL based on the given sizes and updates the source image
	$('#resize').click(function(e) {
		var newSize = mediaURL + currentID + '/' + rotation + '/' + $('#width').val() + '/' + $('#height').val();
		update(newSize);
	})

	// Updates main image to its original size
	$('#original').click(function(e) {
		e.preventDefault();
		var origSize = mediaURL + currentID + '/' + rotation;
		update(origSize);
	});

	// Resets image to default display size
	$('#reset').click(function(e) {
		e.preventDefault();
		var defSize = mediaURL + currentID + '/' + rotation + '/' + defaultSize + '/0';
		update(defSize);
	});

	// Rotate left
	$('#rotate_left').click(function(e) {
		e.preventDefault();
		var img = document.getElementById('main_img');
		rotation += 90;

		var width = img.width;
		var height = img.height;

		if(rotation == 180 || rotation == 360)
		{
			width = img.height;
			height = img.width;
		}

		if(rotation >= 360)
			rotation = 0;

		var newSize = mediaURL + currentID + '/' + rotation + '/' + width + '/' + height; 
		update(newSize);
	});

	// Rotate right
	$('#rotate_right').click(function(e) {
		e.preventDefault();
		var img = document.getElementById('main_img');
		rotation -= 90;

		var width = img.width;
		var height = img.height;

		if(rotation == 180 || rotation == 0)
		{
			width = img.height;
			height = img.width;
		}

		if(rotation < 0)
			rotation = 270;

		var newSize = mediaURL + currentID + '/' + rotation + '/' + width + '/' + height; 
		update(newSize);
	});

	// When main img is clicked, take the main src href and use it
	// to inject into tinymce
	$('#main_href').click(function(e) {
		e.preventDefault();
		inject($(this).attr('href'));
	});

});

// Actual inject method for tinymce to grab the url and return
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
