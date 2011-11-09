<?php

class Ads_Model {

    public static function get_all()
    {
        Database::query('SELECT * FROM {ads} ORDER BY name ASC');
        return Database::fetch_all();
    }

    public static function get_by_cid($cid)
    {
        Database::query('
            SELECT 
                a.*,
                aa.image_width,
                aa.image_height
            FROM {ads} a
                JOIN {ads_areas} aa ON aa.cid = a.area_cid
            WHERE
                a.cid = %s
            ',
            $cid
        );

        if(Database::num_rows() > 0)
            return Database::fetch_array();
        return false;
    }

    public static function create($data)
    {
        $cid = Content::create(ADS_TYPE_AD);
        $status = Database::insert('ads', array(
            'cid' => $cid,
            'area_cid' => $data['area_cid'],
            'media_cid' => $data['media_cid'],
            'name' => $data['name'],
            'url' => $data['url']
        ));

        if($status)
            return $cid;
        return false;
    }

    public static function update($cid, $data)
    {
        Content::update($cid);
        return Database::update('ads',
            array(
                'area_cid' => $data['area_cid'],
                'media_cid' => $data['media_cid'],
                'name' => $data['name'],
                'url' => $data['url']
            ),
            array('cid' => $cid)
        );
    }

    public static function delete($cid)
    {
        $ad = self::get_by_cid($cid);

        // Delete media first
        Media::delete($ad['media_cid']);

        // Delete actual ad
        Content::delete($cid);
        return Database::delete('ads', array('cid' => $cid));
    }

}
