<?php return array(

    'configs' => array(
        'social.twitter_consumer_key' => '',
        'social.twitter_consumer_secret' => '',
        'social.twitter_callback_url' => ''
    ),

    'routes' => array(
        'twitter/oauth' => array(
            'callback' => array('twitter', 'oauth')
        )
    )

); 
