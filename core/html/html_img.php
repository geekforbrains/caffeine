<?php

class Html_Img {

    /**
     * Used for creating an image tag either from a url, or from and ID from the Media module.
     *
     * Example using URL:
     *      Html::img('path/to/image.jpg', array('alt' => 'My Image'));
     *
     * Example using Media ID:
     *      Html::img($mediaId, 0, 250, 250);
     *
     * @param mixed $urlOrID If getting an image via url, this must be the url to the image, otherwise this is the media ID.
     * @param mixed $attributesOrRotation If getting from url, this is the array of attributes otherwise its the image rotation.
     * @param int $width If getting by media ID, this is the image width.
     * @param int $height If getting by media ID, this is the image height.
     * @param array $attributes If getting by media ID, this is the array of attributes.
     *
     * @return An HTML image tag.
     */
    public function get($urlOrID, $attributesOrRotation = null, $width = null, $height = null, $attributes = array())
    {
        if(is_numeric($urlOrID))
            return $this->getMedia($urlOrID, $attributesOrRotation, $width, $height, $attributes);
        
        $attr = '';

        if($attributesOrRotation)
        {
            foreach($attributesOrRotation as $k => $v)
                $attr .= sprintf(' %s="%s"', $k, $v);
        }

        return sprintf('<img src="%s"%s/>', $urlOrID, $attr);
    }

    /**
     * Gets an image tag based on the given Media ID.
     */
    public function getMedia($id, $rotation = null, $wp = null, $h = null, $attributes = array()) {
        return $this->get(Media::image()->getUrl($id, $rotation, $wp, $h), $attributes);
    }

}
