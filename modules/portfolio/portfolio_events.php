<?php 

class Portfolio_Events {

    public static function path_callbacks()
    {
        return array(
            // Front
            'portfolio' => array(
                'title' => 'Portfolio',
                'callback' => array('Portfolio', 'categories'),
                'auth' => true
            ),
            'portfolio/category/%s' => array(
                'title' => 'Portfolio Items',
                'callback' => array('Portfolio', 'items'),
                'auth' => true
            ),

            // Admin Aliases
            'admin/portfolio' => array(
                'title' => 'Portfolio',
                'alias' => 'admin/portfolio/items/manage'
            ),
            'admin/portfolio/items' => array(
                'title' => 'Items',
                'alias' => 'admin/portfolio/items/manage'
            ),
            'admin/portfolio/categories' => array(
                'title' => 'Categories',
                'alias' => 'admin/portfolio/categories/manage'
            ),

            // Admin Items
            'admin/portfolio/items/manage' => array(
                'title' => 'Manage',
                'callback' => array('Portfolio_Admin_Items', 'manage'),
                'auth' => 'manage items'
            ),
            'admin/portfolio/items/create' => array(
                'title' => 'Create',
                'callback' => array('Portfolio_Admin_Items', 'create'),
                'auth' => 'create items'
            ),
            'admin/portfolio/items/edit/%d' => array(
                'title' => 'Edit',
                'callback' => array('Portfolio_Admin_Items', 'edit'),
                'auth' => 'edit items'
            ),
            'admin/portfolio/items/edit/%d/delete-photo/%d' => array(
                'callback' => array('Portfolio_Admin_Items', 'delete_photo'),
                'auth' => 'delete item photos'
            ),
            'admin/portfolio/items/edit/%d/delete-video/%d' => array(
                'callback' => array('Portfolio_Admin_Items', 'delete_video'),
                'auth' => 'delete item videos'
            ),

            // Admin Categories
            'admin/portfolio/categories/manage' => array(
                'title' => 'Manage',
                'callback' => array('Portfolio_Admin_Categories', 'manage'),
                'auth' => 'manage categories'
            ),
            'admin/portfolio/categories/create' => array(
                'title' => 'Create',
                'callback' => array('Portfolio_Admin_Categories', 'create'),
                'auth' => 'create categories'
            ),
            'admin/portfolio/categories/edit/%d' => array(
                'title' => 'Edit',
                'callback' => array('Portfolio_Admin_Categories', 'edit'),
                'auth' => 'edit categories'
            )
        );
    }

    public static function database_install()
    {
        return array(
            'portfolio_categories' => array(
                'fields' => array(
                    'cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'slug' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    ),
                    'name' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    )
                ),

                'indexes' => array(
                    'slug' => array('slug')
                ),

                'primary key' => array('cid')
            ),

            'portfolio_items' => array(
                'fields' => array(
                    'cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'category_cid' => array(
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
                    'description' => array(
                        'type' => 'text',
                        'size' => 'normal',
                        'not null' => true
                    )
                ),

                'indexes' => array(
                    'category_cid' => array('category_cid')
                ),

                'primary key' => array('cid')
            ),

            'portfolio_item_data' => array(
                'fields' => array(
                    'item_cid' => array(
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
                    'value' => array(
                        'type' => 'text',
                        'size' => 'normal',
                        'not null' => true
                    )
                ),

                'indexes' => array(
                    'item_cid' => array('item_cid'),
                    'name' => array('name')
                )
            ),

            'portfolio_item_photos' => array(
                'fields' => array(
                    'item_cid' => array(
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
                    )
                ),

                'indexes' => array(
                    'item_cid' => array('item_cid'),
                    'media_cid' => array('media_cid')
                )
            ),

            'portfolio_item_videos' => array(
                'fields' => array(
                    'item_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'video_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    )
                ),

                'indexes' => array(
                    'item_cid' => array('item_cid'),
                    'video_cid' => array('video_cid')
                )
            )
        );
    }

}
