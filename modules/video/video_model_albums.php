<?php

class Video_Model_Albums {

    public static function get_all()
    {
        Database::query('SELECT * FROM {video_albums} ORDER BY name ASC');

        if(Database::num_rows() > 0)
        {
            $rows = Database::fetch_all();

            foreach($rows as &$row)
                $row['videos'] = Video_Model::get_by_album_cid($row['cid']);

            return $rows;
        }

        return array();
    }

    public static function get_by_cid($cid)
    {
        Database::query('SELECT * FROM {video_albums} WHERE cid = %s', $cid);

        if(Database::num_rows() > 0)
            return Database::fetch_array();
        return false;
    }

    public static function create($name)
    {
        $cid = Content::create(VIDEO_TYPE_ALBUM);
        $status = Database::insert('video_albums', array(
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
        return Database::update('video_albums',
            array('name' => $name),
            array('cid' => $cid)
        );
    }

    public static function delete($cid)
    {
        // First delete all videos associated with album
        $videos = Video_Model::get_by_album_cid($cid);
        foreach($videos as $v); 
            Video_Model::delete($v['cid']);

        Content::delete($cid);

        return Database::delete('video_albums', array('cid' => $cid));
    }

}
