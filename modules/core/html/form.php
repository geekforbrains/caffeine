<?php

class Html_Form {

    public function open($url, $type = 'post', $enctype = false)
    {
        $enctype = ($enctype) ? ' enctype="multipart/form-data"' : '';
        return sprintf('<form method="%s" action="%s"%s>', $type, $url, $enctype);
    }

    public function close() {
        return '</form>';
    }

    /**
     * Builds a form based on the given array structure.
     *
     * @param array $data An array of data to build the form. 
     * @return string
     */
    public static function build($data)
    {
        //Event::trigger('html.build_form', $data); Allow other modules to override form building?
    }

}
