<?php

class Gallery_Model_Photos {

    public static function get_all()
    {
        Database::query('SELECT * FROM {gallery_photos}');
        return Database::fetch_all();
    }

    public static function get_by_album_cid($gallery_cid)
    {
        Database::query('SELECT * FROM {gallery_photos} WHERE album_cid = %s', $gallery_cid);
        return Database::fetch_all();
    }

    public static function get_count_by_album_cid($gallery_cid)
    {
        Database::query('SELECT COUNT(cid) AS count FROM {gallery_photos} WHERE album_cid = %s', $gallery_cid);
        return Database::fetch_single('count');
    }

    public static function get_by_cid($cid)
    {
        Database::query('SELECT * FROM {gallery_photos} WHERE cid = %s', $cid);

        if(Database::num_rows() > 0)
            return Database::fetch_array();
        return false;
    }

    public static function create($data)
    {
        $cid = Content::create(GALLERY_TYPE_PHOTO);
        $status = Database::insert('gallery_photos', array(
            'cid' => $cid,
            'album_cid' => $data['album_cid'],
            'media_cid' => $data['media_cid'],
            'title' => $data['title'],
            'description' => $data['description']
        ));

        if($status)
            return $cid;
        return false;
    }

    public static function update($cid, $data)
    {
        Content::update($cid);
        return Database::update('gallery_photos',
            array(
                'title' => $data['title'],
                'description' => $data['description']
            ),
            array('cid' => $cid)
        );
    }

    public static function delete($cid)
    {
        $photo = self::get_by_cid($cid);
        Media::delete($photo['media_cid']);

        Content::delete($cid);
        return Database::delete('gallery_photos', array('cid' => $cid));
    }

}
