<?php

class Ads_Model_Campaigns {

    public static function get_by_status($status, $limit = null)
    {
        Database::query('
            SELECT
                ac.*,
                a.name AS ad_name
            FROM {ads_campaigns} ac
                JOIN {ads} a ON a.cid = ac.ad_cid
            WHERE
                ac.status = %s
            ',
            $status
        );

        return Database::fetch_all();
    }

    public static function get_by_cid($cid)
    {
        Database::query('
            SELECT
                ac.*,
                a.cid AS ad_cid,
                a.name AS ad_name
            FROM {ads_campaigns} ac
                JOIN {ads} a ON a.cid = ac.ad_cid
            WHERE
                ac.cid = %s
            ',
            $cid
        );

        if(Database::num_rows() > 0)
        {
            $row = Database::fetch_array();
            $totals = self::get_totals($cid);
            $row['impressions'] = $totals['impressions'];
            $row['clicks'] = $totals['clicks'];

            return $row;
        }

        return false;
    }

    public static function get_totals($cid)
    {
        Database::query('SELECT SUM(impressions) AS impressions, SUM(clicks) AS clicks FROM {ads_campaign_stats} WHERE campaign_cid = %s', $cid);
        return Database::fetch_array();
    }

    public static function get_stats($cid)
    {
        Database::query('SELECT * FROM {ads_campaign_stats} WHERE campaign_cid = %s ORDER BY date DESC', $cid);
        return Database::fetch_all();
    }

    public static function create($data)
    {
        $cid = Content::create(ADS_TYPE_CAMPAIGN);
        $status = Database::insert('ads_campaigns', array(
            'cid' => $cid,
            'ad_cid' => $data['ad_cid'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'status' => $data['status']
        ));

        if($status)
            return $cid;
        return false;
    }

}
