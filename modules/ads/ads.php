<?php

class Ads {

    /**
     * Get a random add for the given area. If no ads exist, boolean false is returned.
     */
    public static function get($slug)
    {
        // url is the url to the tracker
        // image is the url using the media library and the areas specified size

        $ad = Ads_Model::get_random_by_slug($slug);
        
        if($ad)
        {
            Ads_Model::update_stat($ad['campaign_cid'], 'impressions');

            return array(
                'url' => Router::url('ads/click/%d', $ad['campaign_cid']),
                'image' => Router::url('media/image/%d/0/%d/%d', $ad['media_cid'], $ad['image_width'], $ad['image_height'])
            );
        }

        return false;
    }

    /**
     * Tracks a clicked ad by its cid and redirects to the url
     */
    public static function click($campaign_cid)
    {
        $url = Ads_Model::get_ad_url($campaign_cid);

        if($url)
        {
            Ads_Model::update_stat($campaign_cid, 'clicks');
            Router::redirect($url);
        }

        die('Internal ad error.');
    }

}
