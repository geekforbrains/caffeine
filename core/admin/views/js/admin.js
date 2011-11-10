$(document).ready(function() {
    $('.datepicker').datepicker({
        dateFomrat: 'yy-mm-dd'
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
});

tinyMCE.init({
    mode : "specific_textareas",
    editor_selector: "tinymce",

    theme : "advanced",
    skin: "caffeine",
    plugins : "media",

    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect",
    theme_advanced_buttons2 : "bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,|,forecolor,backcolor,image,media,|,code,",
    theme_advanced_buttons3 : "",

    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : true,
    theme_advanced_resize_horizontal : false
});

