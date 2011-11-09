<?php

class Ads_Model_Areas {

    public static function get_all()
    {
        Database::query('SELECT * FROM {ads_areas} ORDER BY name ASC');
        return Database::fetch_all();
    }

    public static function get_by_cid($cid)
    {
        Database::query('SELECT * FROM {ads_areas} WHERE cid = %s', $cid);

        if(Database::num_rows() > 0)
            return Database::fetch_array();
        return false;
    }

    public static function create($data)
    {
        $cid = Content::create(ADS_TYPE_AREA);
        $status = Database::insert('ads_areas', array(
            'cid' => $cid,
            'name' => $data['name'],
            'slug' => $data['slug'],
            'image_width' => $data['width'],
            'image_height' => $data['height']
        ));

        if($status)
            return $cid;
        return false;
    }

    public static function update($cid, $data)
    {
        Content::update($cid);
        return Database::update('ads_areas',
            array(
                'name' => $data['name'],
                'slug' => $data['slug'],
                'image_width' => $data['width'],
                'image_height' => $data['height']
            ),
            array('cid' => $cid)
        );
    }

    public static function delete($cid)
    {
        Content::delete($cid);
        return Database::delete('ads_areas', array('cid' => $cid));
    }

}
