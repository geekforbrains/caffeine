<?php

class Gallery {

    public static function albums()
    {
        View::load('Gallery', 'albums', array(
            'albums' => Gallery_Model_Albums::get_all()
        ));
    }

    public static function photos($album_cid)
    {
        View::load('Gallery', 'photos', array(
            'album' => Gallery_Model_Albums::get_by_cid($album_cid),
            'photos' => Gallery_Model_Photos::get_by_album_cid($album_cid)
        ));
    }

    public static function get_album_title($album_cid)
    {
        $album = Gallery_Model_Albums::get_by_cid($album_cid);
        return $album['name']; 
    }

}
