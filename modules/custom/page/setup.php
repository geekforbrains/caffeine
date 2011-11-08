<?php return array(

    'permissions' => array(
        'page.view_own' => 'View own pages.',
        'page.view_all' => 'View all pages.'
    ),
    
    'routes' => array(
        'page/:slug' => 'page/page/single/$1'
    )

);
