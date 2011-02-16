/**
 * -----------------------------------------------------------------------------
 * jQuery callbacks for generic styles such ass date/calendar popup and table
 * row styles.
 * -----------------------------------------------------------------------------
 */
$(document).ready(function() {
    $('.cal').datepicker({
        dateFormat: 'yy-mm-dd'
    });

    $('.checkall').click(function() {
        var checkedStatus = this.checked;
        $('input[type=checkbox]').each(function() {
            this.checked = checkedStatus;
        })
    })

    $('.stripe tr').mouseover(function() {
       $(this).addClass('over')
    });

    $('.stripe tr').mouseout(function() {
       $(this).removeClass('over')
    });

    $('.stripe tr:nth-child(odd)').addClass('alt')

    $('.message-success').slideDown('slow')
    $('.message-success').click(function() {
        $(this).slideToggle('slow');
    })

    $('.message-error').slideDown('slow')
    $('.message-error').click(function() {
        $(this).slideToggle('slow');
    })
});

/**
 * -----------------------------------------------------------------------------
 * Callback method for custon TinyMCE file browser. This method is used with
 * the "Media" module. 
 * -----------------------------------------------------------------------------
 */
function caffeineFileBrowser(field_name, url, type, win) {
	tinyMCE.activeEditor.windowManager.open({
		file : caffeineBaseURL + 'admin/media/dialog/' + type,
		title : 'My File Browser',
		width : 700,
		height : 600,
		resizable : "no",
		inline : "yes",
		close_previous : "no"
	}, {
		window : win,
		input : field_name
	});

	return false;
}

/**
 * -----------------------------------------------------------------------------
 * TinyMCE init with custom settings and custom file browser defined.
 * -----------------------------------------------------------------------------
 */
tinyMCE.init({
    mode : "specific_textareas",
    editor_selector: "tinymce",

    theme : "advanced",
    skin : "o2k7",
	skin_variant : 'silver',
    plugins : "inlinepopups,media",
	dialog_type : "modal",

    theme_advanced_buttons1 : "formatselect,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,link,unlink,anchor,|,image,media,|,code,",
    theme_advanced_buttons2 : "", //"bullist,numlist,|,link,unlink,anchor,|,image,media,|,code,",
    theme_advanced_buttons3 : "",

    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : true,
    theme_advanced_resize_horizontal : false,
	
  	paste_auto_cleanup_on_paste : true,
	
	file_browser_callback : 'caffeineFileBrowser'
});
