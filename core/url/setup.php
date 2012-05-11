<?php return array(

    'events' => array(
        'caffeine.ready' => function()
        {
            // Ignore requests captured for unfound favicon
            if(String::endsWith(Url::current(), 'favicon.ico'))
                return;

            $history = Input::sessionGet('url.history', array());
            array_unshift($history, Url::current());

            if(count($history > 3))
               array_pop($history); 

            Input::sessionSet('url.history', $history);
        }
    )

);
