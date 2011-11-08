<?php return array(

    'configs' => array(
        'view.index' => 'index' . EXT,
        'view.dir' => 'views/'
    ),

    'events' => array(
        'router.data' => function($currentRoute, $routeData)
        {
            if(isset($routeData['title']))
                View::setTitle($routeData['title']);
        }
    )

);
