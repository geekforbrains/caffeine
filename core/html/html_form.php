<?php

class Html_Form {

    public function open($action = null, $method = 'post', $enctype = false)
    {
        if(is_null($action))
            $action = Url::current();

        $enctype = ($enctype) ? ' enctype="multipart/form-data"' : '';
        return sprintf('<form method="%s" action="%s"%s>', $method, $action, $enctype);
    }

    public function close() {
        return '</form>';
    }

    /**
     *  Options:
     *  - type: The type of field (text, textarea, select, checkbox, radio, file, submit, button)
     */
    public function build($fields, $action = null, $method = 'post', $enctype = false)
    {
        $html = Html::form()->open($action, $method, $enctype);

        // Calls the associated method based on the field type
        // Ex: type "textarea" calls self::_textarea($fieldName, $fieldData);
        $html .= '<ul>';

        foreach($fields as $fieldName => $fieldData)
        {
            $html .= '<li';
            
            if(isset($fieldData['class']))
                $html .= sprintf(' class="%s"', $fieldData['class']);
            else
                $html .= sprintf(' class="small %s"', $fieldData['type']);

            $html .= '>';

            if(isset($fieldData['title']))
                $html .= '<label>' . $fieldData['title'] . '</label>';
            $html .= call_user_func(array('self', '_' . $fieldData['type']), $fieldName, $fieldData);
            $html .= '</li>';
        }

        $html .= '</ul>';

        $html .= Html::form()->close();

        return $html;
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
    private static function _text($name, $data) {
        return sprintf('<input%s type="text" name="%s" value="%s" />', self::_attributes($data), $name, self::_default_value($data));
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
                    $html .= sprintf('<option value="%s">%s</option>', $k2, $v2);

                $html .= '</optgroup>';
            }

            // Otherwise create regular option
            else
                $html .= sprintf('<option value="%s">%s</option>', $k, $v);
        }

        $html .= '</select>';
        return $html;
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
