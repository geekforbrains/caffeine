<?php

class Validate_Error {

    private $_properties = array(
        'class' => null,
        'message' => null
    );

    public function __construct($class = null, $message = null)
    {
        $this->_properties['class'] = $class;
        $this->_properties['message'] = $message;
    }

    public function __get($name)
    {
        if(isset($this->_properties[$name]))
            return $this->_properties[$name];

        return null;
    }

    public function getClass($default = null) {
        return trim($default . ' ' . $this->class);
    }

    public function getMessage($default = null)
    {
        if(!is_null($this->message))
            return $this->message;

        return $default;
    }

}
