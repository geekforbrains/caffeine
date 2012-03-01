<?php

/**
$form = Html::form();

$legend = $form->addLegend('My Legend');
$legend->addField('field_name', array());
 
$form->addField('name', array(
    'title' => 'Name',
    'default_value' => 'Yay',
    'options' => array(),
    'validate' => array('required')
));

$form->addButton('submit', 'Create Page');

$form->render();
**/
class Html_Form {

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
        $formName = preg_replace('/[0-9]+/', '', $formId); // Used to set the id/name of the form tag
        $formData = array();

        $html = Html::form()->open($action, $method, $enctype, 
            array('id' => $formName, 'name' => $formName));

        // Insert form id as hidden field
        $html .= '<input type="hidden" name="form_id" value="' . $formId . '" />';

        // Calls the associated method based on the field type
        // Ex: type "textarea" calls self::_textarea($fieldName, $fieldData);
        //$html .= '<ul>';

        foreach($fieldsets as $fieldsetData)
        {
            /*
            $html .= '<fieldset>';

            if(isset($fieldsetData['legend']))
                $html .= '<legend>' . $fieldsetData['legend'] . '</legend>';
            */

            $html .= '<ul>';

            foreach($fieldsetData['fields'] as $fieldName => $fieldData)
            {
                // Check for validation, add to session under form id it present
                if(isset($fieldData['validate']))
                    $formData[$fieldName] = $fieldData;

                // Add form id for buttons
                $fieldData['form_name'] = $formName;

                /*
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

                if(isset($fieldData['content']))
                    $html .= $fieldData['content'];

                $html .= Validate::error($fieldName);
                $html .= '</p>';
                */

                $html .= '<li';
                if(isset($fieldData['class']))
                    $html .= sprintf(' class="%s"', $fieldData['class']);
                else
                {
                    $type = $fieldData['type'] == 'password' ? 'text' : $fieldData['type'];
                    $html .= sprintf(' class="%s"', $type);
                }
                $html .= '>';

                if(isset($fieldData['title']) && !is_null($fieldData['title']))
                    $html .= '<label>' . $fieldData['title'] . '</label>';

                $html .= call_user_func(array('self', '_' . $fieldData['type']), $fieldName, $fieldData);

                if(isset($fieldData['content']))
                    $html .= $fieldData['content'];

                $html .= Validate::error($fieldName);
                $html .= '</li>';
            }

            //$html .= '</fieldset>';
            $html .= '</li>';
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

    private static function _default_value($name, $data) {
        return isset($data['default_value']) ? $data['default_value'] : Input::post($name);
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
        return sprintf('<input%s type="%s" name="%s" value="%s" />', self::_attributes($data), $type, $name, self::_default_value($name, $data));
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
        return sprintf('<textarea%s name="%s">%s</textarea>', self::_attributes($data), $name, self::_default_value($name, $data));
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
                    $html .= sprintf('<option value="%s"%s>%s</option>', $k2, self::_getSelected($name, $data, $k2), $v2);

                $html .= '</optgroup>';
            }

            // Otherwise create regular option
            else
                $html .= sprintf('<option value="%s"%s>%s</option>', $k, self::_getSelected($name, $data, $k), $v);
        }

        $html .= '</select>';
        return $html;
    }

    private static function _getSelected($name, $data, $key)
    {
        if(isset($data['selected']))
        {
            $selected = $data['selected'];
            if((is_array($data) && in_array($key, $data)) || $selected == $key)
                return ' selected="selected"';
        }
        else
        {
            if(Input::post($name) == $key)
                return ' selected="selected"';
        }

        return null;
    }

    private static function _checkbox($name, $data)
    {
        $isChecked = (isset($data['checked']) && $data['checked']) ? 'checked="checked"' : '';
        return sprintf('<input type="checkbox" name="%s"%s />', $name, $isChecked);
    }

    private static function _radio($name, $data)
    {

    }

    private static function _file($name, $data)
    {
        return sprintf('<input type="file" name="%s" />', $name);    
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
    private static function _submit($name, $data)
    {
        //return sprintf('<input%s type="submit" name="%s" value="%s" />', self::_attributes($data), $name, $data['value']);

        $html = sprintf('<input type="hidden" name="%s" value="true" />', $name); // So we can track which button was clicked
        $html .= sprintf('<a class="btn blue" href="javascript:document.%s.submit();">%s</a>', 
            $data['form_name'], $data['value']);

        return $html;
    }

    // TODO
    private static function _button($name, $data)
    {

    }

}
