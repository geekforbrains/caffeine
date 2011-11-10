<?php

class Html_A {

    public function get($title, $url, $attributes = array())
    {
        $attr = '';

        if(!isset($attributes['title']))
            $attributes['title'] = $title;

        if($attributes)
        {
            foreach($attributes as $k => $v)
                $attr .= sprintf(' %s="%s"', $k, $v);

            $attr = trim($attr);
        }

        return sprintf('<a href="%s"%s>%s</a>', Url::to($url), $attr, $title);
    }

}
