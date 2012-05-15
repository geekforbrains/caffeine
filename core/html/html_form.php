<?php

class Html_Form {

    private $_html = '';
    private $_buttons = ''; // Stores buttons to be added at end of form
    private $_token = null; // Stores form security token
    private $_rendered = false;
    private $_inFieldset = false;

    // Validation
    private $_toValidate = array();
    private static $_errors = array();

    /**
     * Create a new form object. Optionally add attributes to the <form> tag.
     */
    public function __construct($args = array())
    {
        $attributes = isset($args[0]) ? $args[0] : null;

        $this->_html = '<form method="post" action="' . Url::current() . '"';

        $defaultClasses = Config::get('html.form_default_classes');

        if(!isset($attributes['class']) && $defaultClasses)
            $attributes['class'] = $defaultClasses;

        if($attributes)
            $this->_html .= $this->_addAttributes(array('attributes' => $attributes));

        $this->_html .= '>';

        $this->_addSecurityToken();
    }

    /**
     * Starts a fieldset and sets the form to add all following input fields to be added
     * the the fieldset until either its closed, another fieldset is created or the form is rendered.
     */
    public function addFieldset($legend = null)
    {
        // If in fieldset previously, close that fieldset first
        if($this->_inFieldset)
            $this->closeFieldset();

        $this->_inFieldset = true;
        $this->_html .= '<fieldset>';

        if(!is_null($legend))
            $this->_html .= '<legend>' . $legend . '</legend>';

        return $this;
    }

    /**
     * Manually closes a fieldset. This is if you want the first part of the form to be a field set but
     * you plan to have elements further down the form outside any fieldsets.
     */
    public function closeFieldset()
    {
        if($this->_inFieldset)
        {
            $this->_inFieldset = false;
            $this->_html .= '</fieldset>';
        }

        return $this;
    }

    /**
     * Creates a text input field.
     *
     * @param $name The field name (name="$name")
     * @param $data An array of optional attributes and data to be added to the field
     *
     * Example:
     *
     *      $form->addText('favorite_color', array(
     *          'title' => 'Whats your favorite color?',
     *          'help' => 'Some help text about the field.'
     *          'attributes' => array(
     *              'class' => 'span3',
     *              'placeholder' => 'Type something...'
     *          ),
     *      ));
     */
    public function addText($name, $data = array())
    {
        $type = (isset($data['is_password']) && $data['is_password']) ? 'password' : 'text';

        $html = '<input type="' . $type . '" name="' . $name . '"';
        $html .= $this->_addDefaultValue($data, ' value="%s"');
        $html .= $this->_addAttributes($data);
        $html .= ' />';

        $this->_addValidation($name, $data);

        return $this->_builder($name, 'text', $html, $data);
    }

    /**
     * Creates a text field as type password.
     */
    public function addPassword($name, $data = array())
    {
        $data['is_password'] = true;
        return $this->addText($name, $data);
    }

    /**
     * Creates a textarea input field.
     */
    public function addTextarea($name, $data = array())
    {
        $html = '<textarea name="' . $name . '"';
        $html .= $this->_addAttributes($data);
        $html .= '>';
        $html .= $this->_addDefaultValue($data, '%s');
        $html .= '</textarea>';

        $this->_addValidation($name, $data);

        return $this->_builder($name, 'textarea', $html, $data);
    }

    /**
     * Creates a select input field, with options.
     *
     * Example:
     *      $form->addSelect('sizes', array('SML', 'MED', 'LRG'), array(
     *          'title' => 'Choose a size',
     *          'selected' => 0 // The option key currently selected, can be an array of selected keys
     *      ));
     */
    public function addSelect($name, $options = array(), $data = array())
    {
        $html = '<select name="' . $name . '"';
        $html .= $this->_addAttributes($data);
        $html .= '>';

        if($options)
        {
            $optionKey = isset($data['option_key']) ? $data['option_key'] : Config::get('html.form_select_option_key');
            $optionValue = isset($data['option_value']) ? $data['option_value'] : Config::get('html.form_select_option_value');

            foreach($options as $k => $v)
            {
                if(is_object($v))
                {
                    $obj = $v;
                    $k = $obj->{$optionKey}; 
                    $v = $obj->{$optionValue};
                }

                $selected = false;

                if(isset($data['selected']))
                {
                    if(is_array($data['selected']) && in_array($k, $data['selected']))
                        $selected = true;

                    elseif($data['selected'] == $k)
                        $selected = true;
                }

                $html .= '<option name="' . $k . '"';

                if($selected)
                    $html .= ' selected="selected"';

                $html .= '>' . $v . '</option>';
            }
        }

        $html .= '</select>';

        $this->_addValidation($name, $data);

        return $this->_builder($name, 'select', $html, $data);
    }

    /**
     * Creates a checkbox input field.
     */
    public function addCheckbox($name, $data)
    {
        $html = '<input type="checkbox" name="' . $name . '"';
        $html .= $this->_addDefaultValue($data, ' value="%s"');
        $html .= $this->_addAttributes($data);

        if(isset($data['checked']) && $data['checked'])
            $html .= ' checked="checked"';

        $html .= ' />';

        $this->_addValidation($name, $data);

        return $this->_builder($name, 'checkbox', $html, $data);
    }

    /**
     * Creates a radio input field.
     */
    public function addRadio($name, $data)
    {
        $html = '<input type="radio" name="' . $name . '"';
        $html .= $this->_addDefaultValue($data, ' value="%s"');
        $html .= $this->_addAttributes($data);

        if(isset($data['checked']) && $data['checked'])
            $html .= ' checked="checked"';

        $html .= ' />';

        $this->_addValidation($name, $data);

        return $this->_builder($name, 'radio', $html, $data);
    }

    /**
     * Creates a submit button, html is handled via setup.php configs.
     */
    public function addSubmit($name, $title, $data = array())
    {
        if(!isset($data['attributes']['class']))
            $data['attributes']['class'] = Config::get('html.form_submit_default_classes');

        $this->_buttons .= sprintf(Config::get('html.form_submit_button'), $this->_addAttributes($data), $name, $title) . '&nbsp;';

        return $this;
    }

    /**
     * Creates a button, html is handled via setup.php configs. Typcially this type of button represents an action
     * for js/ajax requests. Submit buttons should be created via the addSubmit method and link buttons (for going back or changing
     * pages) should be handled via the addLink method.
     */
    public function addButton($name, $title, $data = array()) {}

    /**
     * Creates a link button, html is handled via setup.php configs. These types of buttons are typically
     * used for moving to a new page or going back, not for performing "actions". For actions, us addSubmit or
     * addButton methods.
     */
    public function addLink($url, $title, $data = array())
    {
        if(!isset($data['attributes']['class'])) 
            $data['attributes']['class'] = Config::get('html.form_link_default_classes');

        $this->_buttons .= sprintf(Config::get('html.form_link'), Url::to($url), $this->_addAttributes($data), $title) . '&nbsp;';

        return $this;
    }

    /**
     * Returns the html for the current form object.
     */
    public function render()
    {
        if(!$this->_rendered)
        {
            $this->_buildButtons();
            $this->closeFieldset();
            $this->_html .= '</form>';

            $this->_cacheValidation();
            $this->_rendered = true;
        }

        return $this->_html;
    }

    // --------------------------------------------------------------------------------------------

    /**
     * Takes the HTML for an input along with its data and generates the required HTML output
     * for the type of form being created (basic, inline, fieldset etc.)
     */
    private function _builder($field, $type, $html, $data)
    {
        $wrap = '';

        if($this->_inFieldset)
        {
            $hasError = isset(self::$_errors[$field]);

            if(isset($data['title']))
                $wrap .= sprintf(Config::get('html.form_fieldset_title_wrapper'), $data['title']);

            if($hasError)
                $html .= sprintf(Config::get('html.form_fieldset_error_message'), self::$_errors[$field]);

            if(isset($data['help']))
                $html .= sprintf(Config::get('html.form_fieldset_help_wrapper'), $data['help']);

            $wrap .= sprintf(Config::get('html.form_fieldset_control_wrapper'), $html);

            if($hasError)
                $wrap = sprintf(Config::get('html.form_fieldset_group_error_wrapper'), $wrap);
            else
                $wrap = sprintf(Config::get('html.form_fieldset_group_wrapper'), $wrap);
        }
        else
        {
            switch($type)
            {
                case 'text':
                case 'textarea':
                case 'select':
                    if(isset($data['title']))
                        $wrap .= sprintf(Config::get('html.form_title_wrapper'), $data['title']);

                    $wrap .= $html;
                    break;

                case 'checkbox':
                case 'radio':
                    if(isset($data['title']))
                        $html .= ' ' . $data['title'];

                    $wrap .= sprintf(Config::get('html.form_' . $type . '_wrapper'), $html);
                    break;
            }

            if(isset($data['help']))
                $wrap .= sprintf(Config::get('html.form_help_wrapper', $data['help']));
        }

        $this->_html .= $wrap;

        return $this;
    }

    /**
     * Buttons are a bit special, so we build them on their own when the form is being output.
     */
    private function _buildButtons()
    {
        if(strlen($this->_buttons))
        {
            if($this->_inFieldset)
                $this->_buttons = sprintf(Config::get('html.form_fieldset_buttons_wrapper'), $this->_buttons);

            $this->_html .= $this->_buttons;
        }
    }

    /**
     * TODO
     */
    private function _addDefaultValue($data, $wrapper = '%s')
    {
        if(isset($data['value'])) 
            return sprintf($wrapper, $data['value']);
        return null;
    }

    private function _addAttributes($data)
    {
        if(isset($data['attributes']))
        {
            $str = '';

            foreach($data['attributes'] as $k => $v)
                $str .= sprintf(' %s="%s"', $k, $v);

            return $str;
        }

        return null;
    }

    /**
     * Adds a hidden security token field to the current form object and also adds the token to
     * the users session. This is used to ensure the form is being posted on site.
     */
    private function _addSecurityToken()
    {
        $token = String::random();
        $this->_token = $token;
        $this->_html .= '<input type="hidden" name="form_token" value="' . $token . '" />';

        // Get previous tokens, if any, and add current token to it
        $tokens = Input::sessionGet('form_tokens', array());
        $tokens[] = $token;

        Input::sessionSet('form_tokens', $tokens);
    }

    /**
     * Checks if the current field has validation requirements. If it does, the field is added to
     * a local property that will later be cached and used to process validation.
     */
    private function _addValidation($name, $data)
    {
        if(isset($data['validate']))
            $this->_toValidate[$name] = $data['validate'];
    }

    /**
     * Stores any validation fields into db cache for later processing.
     */
    private function _cacheValidation()
    {
        if($this->_toValidate)
            Cache::store($this->_token, serialize($this->_toValidate));
    }

    // --------------------------------------------------------------------------------------------

    /**
     * Creates a new <form> tag with the given params
     */
    public function open($action = null, $method = 'post', $enctype = false, $attributes = array())
    {
        if(is_null($action))
            $action = Url::toCurrent();
        else
            $action = Url::to($action);

        $attr = '';
        if($attributes)
            foreach($attributes as $k => $v) 
                $attr .= sprintf(' %s="%s"', $k, $v);

        $enctype = ($enctype) ? ' enctype="multipart/form-data"' : '';
        return sprintf('<form method="%s" action="%s"%s%s>', $method, $action, $enctype, $attr);
    }

    /**
     * Does the same thing as the above open() method but has pre-set params for making the form
     * multipart
     */
    public function openMultipart($action = null, $method = 'post') {
        return $this->open($action, $method, true);
    }

    /**
     * Simply adds the form closing tag right now, but this method may later add additional functionality
     * so its best to use it instead of typing the </form> tag itself.
     */
    public function close() {
        return '</form>';
    }

    // --------------------------------------------------------------------------------------------

    /**
     * Checks if the posted form token is valid and within the session. If not, an error message is set and
     * boolean false is returned. This method should be called before processing any form to ensure its security.
     */
    public function isSecure()
    {
        $token = Input::post('form_token');

        if($token && in_array($token, Input::sessionGet('form_tokens')))
            return $token;

        Message::error('Form was submitted insecurely.');
        return false;
    }

    /**
     * Validates the currently posted form (if any) and updates the corresponding form fields with
     * any error messages encountered. Makes use of the Validate module.
     */
    public function validate()
    {
        self::$_errors = array();

        if(!$token = $this->isSecure())
            return false;

        $toValidate = Cache::get(Input::post('form_token'));

        // If no validation is found no checks are performed, so return true
        if($toValidate === false)
            return true;

        $fields = unserialize($toValidate);

        foreach($fields as $field => $checks)
        {
            if(!Validate::check(Input::post($field), $checks))
            {
                Log::debug('html', 'Validation Error: ' . $field . ' - ' . Validate::getLastError());
                self::$_errors[$field] = Validate::getLastError();
            }
        }

        if(self::$_errors)
        {
            Message::error(Config::get('html.form_validation_error'));
            return false;
        }

        return true;
    }

}
