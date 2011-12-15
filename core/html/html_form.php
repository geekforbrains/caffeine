<?php

class Html_Form {

    public function open($action = null, $method = 'post', $enctype = false)
    {
        if(is_null($action))
            $action = Url::current();

        $enctype = ($enctype) ? ' enctype="multipart/form-data"' : '';
        return sprintf('<form method="%s" action="%s"%s>', $method, $action, $enctype);
    }

    public function openMultipart($action = null, $method = 'post') {
        return $this->open($action, $method, true);
    }

    public function close() {
        return '</form>';
    }
    
    /**
     * Clears any form validation data stored with the Cache module. This method is called during the
     * caffeine.start event to clear any un-used form data. 
     */
    public function clear()
    {
        if(isset($_SESSION['forms']))
        {
            foreach($_SESSION['forms'] as $formId)
                Cache::clear($formId);
        }

        unset($_SESSION['forms']);
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

    /**
     *  Options:
     *  - type: The type of field (text, textarea, select, checkbox, radio, file, submit, button)
     */
    public function build($fieldsets, $action = null, $method = 'post', $enctype = false)
    {
        $formId = md5(uniqid()); // Used to determine form being posted when validating feilds
        $formData = array();

        $html = Html::form()->open($action, $method, $enctype);

        // Insert form id as hidden field
        $html .= '<input type="hidden" name="form_id" value="' . $formId . '" />';

        // Calls the associated method based on the field type
        // Ex: type "textarea" calls self::_textarea($fieldName, $fieldData);
        //$html .= '<ul>';

        foreach($fieldsets as $fieldsetData)
        {
            $html .= '<fieldset>';

            if(isset($fieldsetData['legend']))
                $html .= '<legend>' . $fieldsetData['legend'] . '</legend>';

            foreach($fieldsetData['fields'] as $fieldName => $fieldData)
            {
                // Check for validation, add to session under form id it present
                if(isset($fieldData['validate']))
                    $formData[$fieldName] = $fieldData;

                $html .= '<p';
                if(isset($fieldData['class']))
                    $html .= sprintf(' class="%s"', $fieldData['class']);
                else
                {
                    $type = $fieldData['type'] == 'password' ? 'text' : $fieldData['type'];
                    $html .= sprintf(' class="%s"', $type);
                }
                $html .= '>';

                if(isset($fieldData['title']))
                    $html .= '<label>' . $fieldData['title'] . '</label>';
                $html .= call_user_func(array('self', '_' . $fieldData['type']), $fieldName, $fieldData);
                $html .= Validate::error($fieldName);
                $html .= '</p>';
            }

            $html .= '</fieldset>';
        }

        $html .= Html::form()->close();

        // Cache validation fields, if set
        if($formData)
            $this->_cache($formId, $formData);

        return $html;
    }

    /** 
     * Cache form data for validation after posting.
     */
    private static function _cache($formId, $formData)
    {
        if(!isset($_SESSION['forms']))
            $_SESSION['forms'] = array();

        $_SESSION['forms'][] = $formId; // Store form id with session so we can keep track of which data to clear
        Cache::store($formId, serialize($formData));
    }

    private static function _validate()
    {

    }

    private static function _attributes($data)
    {
        $html = '';

        if(isset($data['attributes']))
        {
            $html = ' ';
            foreach($data['attributes'] as $k => $v)
                $html .= sprintf('%s="%s"', $k, $v);
        }

        return $html;
    }

    private static function _default_value($data) {
        return isset($data['default_value']) ? $data['default_value'] : '';
    }

    /**
     * Creates a text input field.
     *
     * <input class="my_class" type="text" name="$name" value="$data[default_value]" />
     *
     * Supported data:
     *      - default_value: The default value to give the field
     *      - attributes: An array of key value pairs for attributes (ex: array('class' => 'my_class'))
     */
    private static function _text($name, $data, $type = 'text') {
        return sprintf('<input%s type="%s" name="%s" value="%s" />', self::_attributes($data), $type, $name, self::_default_value($data));
    }

    // Alias of text input, but as a password type
    private static function _password($name, $data) {
        return self::_text($name, $data, 'password');
    }
    
    /**
     * Creates a textarea input field.
     *
     * <textarea class="my_class" name="$name">$data[default_value]</textarea>
     *
     * Supported data:
     *      - default_value: The default value to give the field
     *      - attributes: An array of key value pairs for attributes (ex: array('class' => 'my_class'))
     */
    private static function _textarea($name, $data) {
        return sprintf('<textarea%s name="%s">%s</textarea>', self::_attributes($data), $name, self::_default_value($data));
    }

    /**
     * Creates a select field with options.
     *
     * <select class="my_class" name="$name">
     *      <option value="$k">$v</option>
     *      <optgroup label="Some Group">
     *          <option value="$k2">$v2</option>
     *      </optgroup>
     * </select>
     *
     * Supported data:
     *      - default_value: The default value to give the field
     *      - attributes: An array of key value pairs for attributes (ex: array('class' => 'my_class'))
     */
    private static function _select($name, $data)
    {
        $html = sprintf('<select%s name="%s">', self::_attributes($data), $name);

        // Get options, are they single array? Then key value pairs, is it a multiarray? Then its a grouped options
        foreach($data['options'] as $k => $v)
        {
            // If value is an array, create group of options
            if(is_array($v))
            {
                $html .= sprintf('<optgroup label="%s">', $k);

                foreach($v as $k2 => $v2)
                    $html .= sprintf('<option value="%s"%s>%s</option>', $k2, self::_getSelected($data, $k2), $v2);

                $html .= '</optgroup>';
            }

            // Otherwise create regular option
            else
                $html .= sprintf('<option value="%s"%s>%s</option>', $k, self::_getSelected($data, $k), $v);
        }

        $html .= '</select>';
        return $html;
    }

    private static function _getSelected($data, $key)
    {
        if(isset($data['selected']))
        {
            $selected = $data['selected'];
            if((is_array($data) && in_array($key, $selected)) || $selected == $key)
                return ' selected="selected"';
        }

        return null;
    }

    private static function _checkbox($name, $data)
    {

    }

    private static function _radio($name, $data)
    {

    }

    private static function _file($name, $data)
    {
    
    }

    /**
     * Creates a submit button.
     *
     * <input class="my_class" type="submit" value="$data[value]" />
     *
     * Supported data:
     *      - default_value: The default value to give the field
     *      - attributes: An array of key value pairs for attributes (ex: array('class' => 'my_class'))
     */
    private static function _submit($name, $data) {
        return sprintf('<input%s type="submit" name="%s" value="%s" />', self::_attributes($data), $name, $data['value']);
    }

    private static function _button($name, $data)
    {

    }

}
