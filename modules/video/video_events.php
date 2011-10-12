<?php 

class Video_Events {

    public static function path_callbacks()
    {
        return array(
            // Front
            'videos' => array(
                'title' => 'Videos',
                'callback' => array('Video', 'albums'),
                'auth' => true
            ),
            'videos/%d' => array(
                'title' => 'Album',
                'title_callback' => array('Video', 'get_album_title'),
                'callback' => array('Video', 'videos'),
                'auth' => true
            ),
            'videos/view/%d' => array(
                'title' => 'Video',
                'title_callback' => array('Video', 'get_video_title'),
                'callback' => array('Video', 'view'),
                'auth' => true
            ),

            // Admin
            'admin/video' => array(
                'title' => 'Videos',
                'alias' => 'admin/video/manage'
            ),
            'admin/video/manage' => array(
                'title' => 'Manage',
                'callback' => array('Video_Admin', 'manage'),
                'auth' => 'manage video albums'
            ),
            'admin/video/create' => array(
                'title' => 'Create',
                'callback' => array('Video_Admin', 'create'),
                'auth' => 'create video albums'
            ),
            'admin/video/edit/%d' => array(
                'title' => 'Edit',
                'callback' => array('Video_Admin', 'edit'),
                'auth' => 'edit video albums'
            ),
            'admin/video/edit/%d/delete-video/%d' => array(
                'callback' => array('Video_Admin', 'delete_video'),
                'auth' => 'delete videos'
            ),
            'admin/video/delete/%d' => array(
                'callback' => array('Video_Admin', 'delete'),
                'auth' => 'delete video albums'
            )
        );
    }

    public static function database_install()
    {
        return array(
            'video_albums' => array(
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

            'videos' => array(
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
                    'media_cid' => array( // Image stored from thumbnail url
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'video_id' => array( // Actual video id from youtube or vimeo
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    ),
                    'title' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    ),
                    'description' => array(
                        'type' => 'text',
                        'not null' => true
                    ),
                    'url' => array(
                        'type' => 'text',
                        'size' => 'tiny',
                        'not null' => true
                    )
                ),

                'indexes' => array(
                    'album_cid' => array('album_cid'),
                    'media_cid' => array('media_cid'),
                    'video_id' => array('video_id')
                ),

                'primary key' => array('cid')
            )
        );
    }

}
