$(function() {
    $('table tr').mouseover(function() {
        $(this).addClass('hover')
    });

    $('table tr').mouseout(function() {
        $(this).removeClass('hover')
    });

    $('table tr:nth-child(even)').addClass('alt');

    $('.checkall').click(function() {
        var checkedStatus = this.checked;

        $('input[type=checkbox]').each(function() {
            this.checked = checkedStatus;
        })
    })

    $(".sortable .sort_items").sortable();
    $(".sortable .sort_items").disableSelection();

    $(".modal").click(function() {
        $( "#modal" ).dialog({
            height: 140,
            modal: true
        })
    });

    $('textarea.tinymce').tinymce({
        script_url : baseHref + 'plugins/tiny_mce/tiny_mce.js',

        theme : "advanced",
        plugins: "inlinepopups",

        theme_advanced_buttons1 : "formatselect,fontsizeselect,|,bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,anchor,image,code",
        theme_advanced_buttons2 : "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true
    });
});
