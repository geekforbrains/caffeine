<?php
/**
 * Dont allow cron to be run via the browser, it must be run from CLI
 */
if(isset($_SERVER['HTTP_HOST']))
{
    header('HTTP/1.1 403 Forbidden');
    exit(1);
}

define('CLI', true); // Put Caffeine into CLI mode
require('index.php');
Event::trigger('cron.run');
