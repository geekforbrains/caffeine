<?php

class Gallery_Model_Albums {

    public static function get_all()
    {
        Database::query('SELECT * FROM {gallery_albums} ORDER BY name ASC');
        return Database::fetch_all();
    }

    public static function get_by_cid($cid)
    {
        Database::query('SELECT * FROM {gallery_albums} WHERE cid = %s', $cid);

        if(Database::num_rows() > 0)
            return Database::fetch_array();
        return false;
    }

    public static function create($name)
    {
        $cid = Content::create(GALLERY_TYPE_ALBUM);
        $status = Database::insert('gallery_albums', array(
            'cid' => $cid,
            'name' => $name
        ));

        if($status)
            return $cid;
        return false;
    }

    public static function update($cid, $name)
    {
        Content::update($cid);
        return Database::update('gallery_albums', 
            array('name' => $name),
            array('cid' => $cid)
        );
    }

    public static function update_weight($cid, $weight)
    {
        Content::update($cid);
        return Database::update('gallery_albums', 
            array('weight' => $weight),
            array('cid' => $cid)
        );
    }

    public static function delete($cid)
    {
        // First delete photos associated with album
        $photos = Gallery_Model_Photos::get_by_album_cid($cid);
        foreach($photos as $p)
            Gallery_Model_Photos::delete($p['cid']);

        Content::delete($cid);
        return Database::delete('gallery_albums', array('cid' => $cid));
    }

}
