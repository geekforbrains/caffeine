<?php

class Ads_Events {

    public static function path_callbacks()
    {
        return array(
            'admin/ads' => array(
                'title' => 'Ads',
                'alias' => 'admin/ads/campaigns/manage'
            ),

            // Campaigns
            'admin/ads/campaigns' => array(
                'title' => 'Campaigns',
                'alias' => 'admin/ads/campaigns/manage'
            ),
            'admin/ads/campaigns/manage' => array(
                'title' => 'Manage',
                'callback' => array('Ads_Admin_Campaigns', 'manage'),
                'auth' => 'manage campaigns'
            ),
            'admin/ads/campaigns/create' => array(
                'title' => 'Create',
                'callback' => array('Ads_Admin_Campaigns', 'create'),
                'auth' => 'create campaigns'
            ),
            'admin/ads/campaigns/details/%d' => array(
                'callback' => array('Ads_Admin_Campaigns', 'details'),
                'auth' => 'view campaign details'
            ),
            'admin/ads/campaigns/stop/%d' => array(
                'callback' => array('Ads_Admin_Campaigns', 'stop'),
                'auth' => 'stop campaigns'
            ),

            // Ads
            'admin/ads/ads' => array(
                'title' => 'Ads',
                'alias' => 'admin/ads/ads/manage'
            ),
            'admin/ads/ads/manage' => array(
                'title' => 'Manage',
                'callback' => array('Ads_Admin', 'manage'),
                'auth' => 'manage ads'
            ),
            'admin/ads/ads/create' => array(
                'title' => 'Create',
                'callback' => array('Ads_Admin', 'create'),
                'auth' => 'create ads'
            ),
            'admin/ads/ads/edit/%d' => array(
                'callback' => array('Ads_Admin', 'edit'),
                'auth' => 'edit ads'
            ),
            'admin/ads/ads/delete/%d' => array(
                'callback' => array('Ads_Admin', 'delete'),
                'auth' => 'delete ads'
            ),

            // Areas
            'admin/ads/areas' => array(
                'title' => 'Areas',
                'alias' => 'admin/ads/areas/manage'
            ),
            'admin/ads/areas/manage' => array(
                'title' => 'Manage',
                'callback' => array('Ads_Admin_Areas', 'manage'),
                'auth' => 'manage areas'
            ),
            'admin/ads/areas/create' => array(
                'title' => 'Create',
                'callback' => array('Ads_Admin_Areas', 'create'),
                'auth' => 'create areas'
            ),
            'admin/ads/areas/edit/%d' => array(
                'callback' => array('Ads_Admin_Areas', 'edit'),
                'auth' => 'edit areas'
            ),
            'admin/ads/areas/delete/%d' => array(
                'callback' => array('Ads_Admin_Areas', 'delete'),
                'auth' => 'delete areas'
            )
        );
    }

    public static function database_install()
    {
        // Areas are spots on the site with a specific image size
        // Ads are created for areas with an image and a destination url
        // Campaigns are active ads for a time period with tracked stats
        return array(
            'ads_areas' => array(
                'fields' => array(
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

                'indexes' => array(
                    'slug' => array('slug')
                ),

                'primary key' => array('cid')
            ),

            'ads' => array(
                'fields' => array(
                    'cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'area_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'media_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'name' => array(
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

                'indexes' => array(
                    'area_cid' => array('area_cid'),
                    'media_cid' => array('media_cid')
                ),
                
                'primary key' => array('cid')
            ),

            'ads_campaigns' => array(
                'fields' => array(
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
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'end_date' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'status' => array( // active, complete, stopped
                        'type' => 'varchar',
                        'length' => 10,
                        'not null' => true
                    )
                ),
                
                'indexes' => array(
                    'ad_cid' => array('ad_cid'),
                    'start_date' => array('start_date'),
                    'end_date' => array('end_date'),
                    'status' => array('status')
                ),

                'primary key' => array('cid')
            ),

            'ads_campaign_stats' => array(
                'fields' => array(
                    'campaign_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'impressions' => array(
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
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    )
                ),

                'indexes' => array(
                    'campaign_cid' => array('campaign_cid'),
                    'impressions' => array('impressions'),
                    'clicks' => array('clicks'),
                    'date' => array('date')
                )
            )
        );
    }

}
