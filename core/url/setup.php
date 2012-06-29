<?php return array(

    'configs' => array(
        /**
         * This is used when determining if a subdomain exists in the url. The TLD count represents the number
         * of TLD's used in the base domain.
         *
         * Example: mydomain.com = 1 TLD
         * Example: mydomain.co.uk = 2 TLD's
         *
         * Normally this will not need to be changed as it supports all the most common (.com, .net, .org. .info etc..)
         */
        'url.tld_count' => 1
    ),

    'events' => array(
        /**
         * Used to keep track of URL history while user browses the site. This is mainly used
         * with the Url::previous() method.
         */
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
