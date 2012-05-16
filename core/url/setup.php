<?php return array(

    'events' => array(
        'caffeine.ready' => function()
        {
            // Ignore requests captured for unfound favicon
            if(String::endsWith(Url::current(), 'favicon.ico'))
                return;

            $history = Input::sessionGet('url_history', array());
            $current = Url::current();

            // Dont add multiple histories for the same page consecutively
            if(isset($history[0]) && $history[0] == $current)
                return;

            array_unshift($history, $current);

            if(count($history) > 3)
               array_pop($history); 

            Input::sessionSet('url_history', $history);
        }
    )

);
