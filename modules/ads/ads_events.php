<?php

class Ads_Events {

    public static function path_callbacks()
    {
        return array(
            'admin/ads' => array(
                'title' => 'Ads',
                'alias' => 'admin/ads/campaigns/manage'
            ),
        );
    }

    public static function database_install()
    {
        // Areas are spots on the site with a specific image size
        // Ads are created for areas with an image and a destination url
        // Campaigns are active ads for a time period with tracked stats
        return array(
            'ads_areas' => array(
                'cid' => array(
                    'type' => 'int',
                    'size' => 'big',
                    'unsigned' => true
                ),
                'name' => array(
                    'type' => 'varchar',
                    'length' => 255,
                    'not null' => true
                ),
                'slug' => array(
                    'type' => 'varchar',
                    'length' => 255,
                    'not null' => true
                ),
                'image_width' => array(
                    'type' => 'int',
                    'size' => 'big',
                    'unsigned' => true
                ),
                'image_height' => array(
                    'type' => 'int',
                    'size' => 'big',
                    'unsigned' => true
                )
            ),

            'ads' => array(
                'cid' => array(
                    'type' => 'int',
                    'size' => 'big',
                    'unsigned' => true,
                    'not null' => true
                ),
                'media_cid' => array(
                    'type' => 'varchar',
                    'length' => 255,
                    'not null' => true
                ),
                'url' => array(
                    'type' => 'text',
                    'size' => 'normal',
                    'not null' => true
                )
            ),

            'ads_campaigns' => array(
                'cid' => array(
                    'type' => 'int',
                    'size' => 'big',
                    'unsigned' => true
                ),
                'ad_cid' => array(
                    'type' => 'int',
                    'size' => 'big',
                    'unsigned' => true
                ),
                'start_date' => array(
                    'type' => 'date',
                    'not null' => true
                ),
                'end_date' => array(
                    'type' => 'date',
                    'not null' => true
                )
            ),

            'ads_campaign_stats' => array(
                'campaign_cid' => array(
                    'type' => 'int',
                    'size' => 'big',
                    'unsigned' => true,
                    'not null' => true
                ),
                'displayed' => array(
                    'type' => 'int',
                    'size' => 'big',
                    'not null' => true
                ),
                'clicks' => array(
                    'type' => 'int',
                    'size' => 'normal',
                    'not null' => true
                ),
                'date' => array(
                    'type' => 'date',
                    'not null' => true
                )
            )
        );
    }

}
