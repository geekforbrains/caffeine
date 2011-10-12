<?php

class Video_Model {

    public static function get_all()
    {
        Database::query('SELECT * FROM {videos}');
        return Database::fetch_all();
    }

    public static function get_by_album_cid($album_cid)
    {
        Database::query('
            SELECT 
                v.*,
                c.created
            FROM {videos} v
                LEFT JOIN {content} c ON c.id = v.cid
            WHERE 
                album_cid = %s
            ORDER BY
                c.created DESC
            ', 
            $album_cid
        );

        return Database::fetch_all();
    }

    public static function get_latest()
    {
        Database::query('
            SELECT 
                v.*,
                c.created
            FROM {videos} v
                LEFT JOIN {content} c ON c.id = v.cid
            ORDER BY
                c.created DESC
            LIMIT 1
        ');

        return Database::fetch_array();
    }

    public static function get_by_cid($cid)
    {
        Database::query('SELECT * FROM {videos} WHERE cid = %s', $cid);

        if(Database::num_rows() > 0)
            return Database::fetch_array();
        return false;
    }

    public static function create($url, $album_cid, $media_cid, $data)
    {
        $cid = Content::create(VIDEO_TYPE_VIDEO);
        $status = Database::insert('videos', array(
            'cid' => $cid,
            'album_cid' => $album_cid,
            'media_cid' => $media_cid,
            'video_id' => $data['id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'url' => $url
        ));

        if($status)
            return $cid;
        return false;
    }

    public static function delete($cid)
    {
        $video = self::get_by_cid($cid);
        Media::delete($video['media_cid']);

        Content::delete($cid);
        return Database::delete('videos', array('cid' => $cid));
    }

}
