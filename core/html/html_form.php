<?php

class Html_Form {

    private $_html = '';
    private $_rendered = false;
    private $_inFieldset = false;

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
    }

    /**
     * TODO
     */
    public function addFieldset($legend = null)
    {
        $this->_inFieldset = true;
        $this->_html .= '<fieldset>';

        if(!is_null($legend))
            $this->_html .= '<legend>' . $legend . '</legend>';
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
     *          'validate' => array('required', 'min:3'),
     *          'attributes' => array(
     *              'class' => 'span3',
     *              'placeholder' => 'Type something...'
     *          ),
     *      ));
     */
    public function addText($name, $data = array())
    {
        $this->_html .= $this->_wrapTitle($data);

        $type = (isset($data['is_password']) && $data['is_password']) ? 'password' : 'text';

        $this->_html .= '<input type="' . $type . '" name="' . $name . '"';
        $this->_html .= $this->_addDefaultValue($data, ' value="%s"');
        $this->_html .= $this->_addAttributes($data);
        $this->_html .= ' />';

        $this->_html .= $this->_addhelp($data);

        return $this;
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
        $this->_html .= $this->_wrapTitle($data);

        $this->_html .= '<textarea name="' . $name . '"';
        $this->_html .= $this->_addAttributes($data);
        $this->_html .= '>';
        $this->_html .= $this->_addDefaultValue($data, '%s');
        $this->_html .= '</textarea>';

        $this->_html .= $this->_addHelp($data);

        return $this;
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
        $this->_html .= $this->_wrapTitle($data);

        $this->_html .= '<select name="' . $name . '"';
        $this->_html .= $this->_addAttributes($data);
        $this->_html .= '>';

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

                $this->_html .= '<option name="' . $k . '"';

                if($selected)
                    $this->_html .= ' selected="selected"';

                $this->_html .= '>' . $v . '</option>';
            }
        }

        $this->_html .= '</select>';

        $this->_html .= $this->_addHelp($data);

        return $this;
    }

    /**
     * Creates a checkbox input field.
     */
    public function addCheckbox($name, $data)
    {
        $html = '';

        $html .= '<input type="checkbox" name="' . $name . '"';
        $html .= $this->_addDefaultValue($data, ' value="%s"');
        $html .= $this->_addAttributes($data);

        if(isset($data['checked']) && $data['checked'])
            $html .= ' checked="checked"';

        $html .= ' />';

        if(isset($data['title']))
            $html .= ' ' . $data['title'];

        $this->_html = sprintf(Config::get('html.form_checkbox_wrapper', $html));

        return $this;
    }

    /**
     * Creates a radio input field.
     */
    public function addRadio()
    {
        $html = '';

        $html .= '<input type="radio" name="' . $name . '"';
        $html .= $this->_addDefaultValue($data, ' value="%s"');
        $html .= $this->_addAttributes($data);

        if(isset($data['checked']) && $data['checked'])
            $html .= ' checked="checked"';

        $html .= ' />';

        if(isset($data['title']))
            $html .= ' ' . $data['title'];

        $this->_html = sprintf(Config::get('html.form_radio_wrapper', $html));

        return $this;
    }

    /**
     * Creates a submit button, html is handled via setup.php configs.
     */
    public function addSubmit($name, $title) {
        $this->_html .= sprintf(Config::get('html.form_submit_button'), $name, $title);
    }

    /**
     * TODO Creates a button, html is handled via setup.php configs. (Ex: Cancel button that goes back)
     */
    public function addButton($name, $title, $data) {}

    /**
     * Returns the html for the current form object.
     */
    public function render()
    {
        if(!$this->_rendered)
        {
            $this->_html .= '</form>';
            $this->_rendered = true;
        }

        return $this->_html;
    }

    // --------------------------------------------------------------------------------------------

    private function _wrapTitle($data)
    {
        if(isset($data['title']))
            return sprintf(Config::get('html.form_title_wrapper'), $data['title']);
        return null;
    }

    private function _addHelp($data)
    {
        if(isset($data['help']))
            return sprintf(Config::get('html.form_help_wrapper'), $data['help']);
        return null;
    }

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

    // --------------------------------------------------------------------------------------------

    /**
     * TODO Comments.
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
     * TODO Comments.
     */
    public function openMultipart($action = null, $method = 'post') {
        return $this->open($action, $method, true);
    }

    /**
     * TODO Comments.
     */
    public function close() {
        return '</form>';
    }

    /**
     * Validates the stored fields in session based on the given form id.
     */
    public function validate()
    {
        $formId = $_POST['form_id'];
        $data = Cache::get($formId);

        if($data)
        {
            $fields = unserialize($data);

            foreach($fields as $fieldName => $fieldData)
                if(isset($fieldData['validate']))
                    Validate::check($fieldName, $fieldData['validate']);

            return Validate::passed();
        }

        return false;
    }

}
