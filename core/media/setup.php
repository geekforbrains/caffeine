<?php return array(

    'configs' => array(
        'media.allowed_file_formats' => array(), // Leave blank for anything
        'media.allowed_image_formats' => array('.gif', '.png', '.jpg', '.jpeg'),
        'media.allowed_video_formats' => array('.avi', '.mwv', '.mov'),

        'media.media_dir' => 'media/' . date('Y/m') . '/', // Must have trailing slash
        'media.cache_dir' => 'cache/', // Must have trailing slash
        'media.files_dir' => 'files/' // Must have trailing slash
    ),

    'routes' => array(
        // id
        'media/image/:num' => array(
            'callback' => array('image', 'render')
        ),

        // id, rotation
        'media/image/:num/:num' => array(
            'callback' => array('image', 'render')
        ),

        // id, rotation, percent
        'media/image/:num/:num/:num' => array(
            'callback' => array('image', 'render')
        ),

        // id, rotation, width, height
        'media/image/:num/:num/:num/:num' => array(
            'callback' => array('image', 'render')
        ),

        'media/download/:num' => array(

        )
    )

);
