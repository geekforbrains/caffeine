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

    public static function get_random_by_slug($slug)
    {
        self::check_dates();

        Database::query('
            SELECT
                a.*,
                aa.image_width,
                aa.image_height,
                ac.cid AS campaign_cid
            FROM {ads_areas} aa
                JOIN {ads} a ON a.area_cid = aa.cid
                JOIN {ads_campaigns} ac ON ac.ad_cid = a.cid
            WHERE
                aa.slug = %s
                AND ac.status = %s
            ORDER BY rand()
            LIMIT 1
            ',
            $slug,
            'active'
        );

        if(Database::num_rows() > 0)
            return Database::fetch_array();
        return false;
    }

    public static function get_ad_url($campaign_cid)
    {
        Database::query('
            SELECT 
                a.url
            FROM {ads_campaigns} ac
                JOIN {ads} a ON a.cid = ac.ad_cid
            WHERE ac.cid = %s
            ', 
            $campaign_cid
        );
        
        if(Database::num_rows() > 0)
            return Database::fetch_single('url');
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

    public static function update_stat($campaign_cid, $field)
    {
        self::check_stats($campaign_cid);

        Database::query('
            UPDATE {ads_campaign_stats} SET ' . $field . '='. $field . ' + 1 
            WHERE campaign_cid = %s', $campaign_cid
        );
    }

    // Ensures stats for the given campaign exist for today
    public static function check_stats($campaign_cid)
    {
        Database::query('SELECT * FROM {ads_campaign_stats} WHERE campaign_cid = %s AND date = %s', $campaign_cid, strtotime('today'));

        if(Database::num_rows() <= 0)
        {
            Database::insert('ads_campaign_stats', array(
                'campaign_cid' => $campaign_cid,
                'date' => strtotime('today')
            ));
        }
    }

    // Check for any campaigns expiring, and update accordingly
    public static function check_dates()
    {
        Database::query('UPDATE {ads_campaigns} SET status = %s WHERE end_date <= %s',
            'complete', strtotime('today'));
    }

}
