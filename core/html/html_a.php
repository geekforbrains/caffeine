<?php

class Html_A {

    /**
     * Creates an HTML anchor tag. If the given url matches the first part of current url, a class of "active" will automatically
     * be added to the attributes.
     *
     * Basic Example:
     * Html::a()->get('My Text', 'some/path');
     *
     * Attributes Example:
     * Html::a()->get('My Text', 'some/path', array('title' => 'My Text', 'class' => 'active'));
     *
     * @param string $title The text to go between the tags
     * @param string $url The relative or full url
     * @param array $attributes An optional array of attributes where the key is the attribute name
     * @return string An HTML anchor tag
     */
    public function get($title, $url, $attributes = array())
    {
        $attr = '';

        if($url != '/' && strstr(Url::current(true), $url))
        {
            if(isset($attributes['class']))
                $attributes['class'] = 'active ' . $attributes['class'];
            else
                $attributes['class'] = 'active';
        }
        elseif($url == '/' && !strlen(Url::current(true)))
                $attributes['class'] = 'active';

        if($attributes)
        {
            foreach($attributes as $k => $v)
                $attr .= sprintf(' %s="%s"', $k, $v);
        }

        $url = is_null($url) ? 'javascript:null' : Url::to($url);
        return sprintf('<a href="%s"%s>%s</a>', $url, $attr, $title);
    }

}
