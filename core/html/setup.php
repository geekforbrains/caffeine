<?php return array(

    'configs' => array(

        // ------- TABLES --------

        /**
         * The default CSS classes applied to any table created using the Html::table builder.
         */
        'html.table_default_classes' => 'table table-striped table-condensed',

        // ------- FORMS --------

        /**
         * The default classes added to the <form> tag if none are set manually.
         */
        'html.form_default_classes' => 'well',

        /**
         * Form HTML wrappers, use sprintf() format.
         */
        'html.form_title_wrapper' => '<label>%s</label>',
        'html.form_help_wrapper' => '<span class="help-block">%s</span>',
        'html.form_checkbox_wrapper' => '<label class="checkbox">%s</label>',
        'html.form_radio_wrapper' => '<label class="radio">%s</label>',

        /**
         * Form HTML wrappers specific to fieldsets.
         */
        'html.form_fieldset_group_wrapper' => '<div class="control-group">%s</div>',
        'html.form_fieldset_control_wrapper' => '<div class="controls">%s</div>',
        'html.form_fieldset_title_wrapper' => '<label class="control-label">%s</label>',
        'html.form_fieldset_help_wrapper' => '<p class="help-block">%s</p>',
        'html.form_fieldset_buttons_wrapper' => '<div class="form-actions">%s</div>',

        /**
         * Form buttons HTML, use sprintf() format.
         */
        'html.form_submit_button' => '<input type="submit" %s name="%s" value="%s" />',
        'html.form_submit_default_classes' => 'btn btn-primary',

        'html.form_button' => '<button %s>%s</button>',
        'html.form_button_default_classes' => 'btn',

        'html.form_link' => '<a href="%s" %s>%s</a>',
        'html.form_link_default_classes' => 'btn',

        /**
         * The below configs are used when creating options for select input where
         * the options are objects instead of an array of key value pairs. These typically should not
         * be changed. Instead use the 'option_key' and 'option_value' params when creating the
         * form field. Below are the defaults when params aren't set.
         */
        'html.form_select_option_key' => 'id', // Ex: $obj->id
        'html.form_select_option_value' => 'name', // Ex: $obj->name
    )

);
