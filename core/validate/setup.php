<?php return array(

    'configs' => array(
        /**
         * The default error message displayed if validation checks fail.
         */
        'validate.error_message' => 'Missing or invalid fields detected.',

        /**
         * The class returned when e('field')->class is called.
         */
        'validate.error_class' => 'error',

        /**
         * Below are the messages associated with validation checks.
         */
        'validate.required_error' => 'is required.',
        'validate.email_error' => 'is not a valid email.',
        'validate.matches_error' => 'does not match.',
        'validate.min_error' => 'must be a minimum of %d characters.',
        'validate.numeric_error' => 'must be a numeric value.'
    )

);
