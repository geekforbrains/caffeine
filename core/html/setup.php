<?php return array(

    'configs' => array(
        /**
         * The default CSS classes applied to any table created using the Html::table builder.
         */
        'html.table_default_classes' => 'table table-striped table-condensed',

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
        'html.form_fieldset_help_wrapper' => '<p class="help-block">%s</p>,

        /**
         * Form buttons, use sprintf() format.
         */
        'html.form_submit_button' => '<input type="submit" class="btn btn-primary" name="%s" value="%s" />',

        /**
         * The below configs are used when creating options for select input where
         * the options are objects instead of array key value pairs. These typically should not
         * be changed. Instead use the 'option_key' and 'option_value' params when creating the
         * form field. Below are the defaults when params aren't set.
         */
        'html.form_select_option_key' => 'id',
        'html.form_select_option_value' => 'name',
    )

);
