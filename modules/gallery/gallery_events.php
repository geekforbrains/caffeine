<?php

class Gallery_Events {

    public static function path_callbacks()
    {
        return array(
            // Front
            'gallery' => array(
                'title' => 'Photo Gallery',
                'callback' => array('Gallery', 'albums'),
                'auth' => true
            ),
            'gallery/%d' => array(
                'title' => 'Album',
                'title_callback' => array('Gallery', 'get_album_title'),
                'callback' => array('Gallery', 'photos'),
                'auth' => true
            ),

            // Admin
            'admin/gallery' => array(
                'title' => 'Gallery',
                'alias' => 'admin/gallery/manage'
            ),
            'admin/gallery/manage' => array(
                'title' => 'Manage Albums',
                'callback' => array('Gallery_Admin', 'manage'),
                'auth' => 'manage gallery albums'
            ),
            'admin/gallery/create' => array(
                'title' => 'Create Album',
                'callback' => array('Gallery_Admin', 'create'),
                'auth' => 'create gallery albums'
            ),
            'admin/gallery/edit/%d' => array(
                'title' => 'Edit Album',
                'callback' => array('Gallery_Admin', 'edit'),
                'auth' => 'edit gallery albums'
            ),
            'admin/gallery/edit/%d/edit-photo/%d' => array(
                'title' => 'Edit Photo',
                'callback' => array('Gallery_Admin', 'edit_photo'),
                'auth' => 'edit album photos'
            ),
            'admin/gallery/edit/%d/delete-photo/%d' => array(
                'callback' => array('Gallery_Admin', 'delete_photo'),
                'auth' => 'delete album photos'
            ),
            'admin/gallery/delete/%d' => array(
                'callback' => array('Gallery_Admin', 'delete'),
                'auth' => 'delete gallery albums'
            )
        );
    }


    public static function database_install()
    {
        return array(
            'gallery_albums' => array(
                'fields' => array(
                    'cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'name' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    )
                ),

                'primary key' => array('cid')
            ),

            'gallery_photos' => array(
                'fields' => array(
                    'cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'album_cid' => array(
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
                    'title' => array(
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
                    'album_cid' => array('album_cid'),
                    'media_cid' => array('media_cid')
                ),
    
                'primary key' => array('cid')
            )
        );
    }

}
