<?php

class Html_Img {

    public function get($url, $attributes = array())
    {
        $attr = '';

        if($attributes)
        {
            foreach($attributes as $k => $v)
                $attr .= sprintf(' %s="%s"', $k, $v);
        }

        return sprintf('<img src="%s"%s/>', $url, $attr);
    }

    public function getMedia($id, $rotation = null, $wp = null, $h = null, $attributes = array()) {
        return $this->get(Media::image()->getUrl($id, $rotation, $wp, $h), $attributes);
    }

}
