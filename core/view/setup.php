<?php return array(


    'configs' => array(
        'view.cache_js' => false,
        'view.cache_css' => false,
        'view.index' => 'index' . EXT,
        'view.dir' => 'views/'
    ),


    'routes' => array(
        'view/js/:any' => array(
            'callback' => array('view', 'js')
        ),
        'view/css/:any' => array(
            'callback' => array('view', 'css')
        )
    ),


    'events' => array(
        'router.data' => function($currentRoute, $routeData)
        {
            if(isset($routeData['title']))
                View::setTitle($routeData['title']);
        }
    )


);
