<?php return array(

    'configs' => array(
        'media.allowed_file_formats' => array(), // Leave blank for anything
        'media.allowed_image_formats' => array('.gif', '.png', '.jpg', '.jpeg'),
        'media.allowed_video_formats' => array('.avi', '.mwv', '.mov'),

        'media.media_dir' => 'media/' . date('Y/m') . '/', // Must have trailing slash
        'media.cache_dir' => 'media_cache/', // Must have trailing slash
        'media.files_dir' => 'files/', // Must have trailing slash
        'media.dir_chmod' => 0775, // The chmod value set on file directories created (must be writable)

        'media.youtube_api' => 'http://gdata.youtube.com/feeds/api/videos/%s',
        'media.vimeo_api' => 'http://vimeo.com/api/clip/%s.xml'
    ),

    'routes' => array(
        // placeholder (width / height)
        'media/placeholder/:num/:num' => array(
            'callback' => array('image', 'placeholder')
        ),

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

        // download file through browser by id
        'media/download/:num' => array(
            'callback' => array('file', 'download')
        )
    )

);
