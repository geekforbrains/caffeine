<?php
/**
 * The passcode is used to ensure only those authorized can run
 * a cron job. Change the below value to something of your choice.
 *
 * When running the cron job from CLI, use this passcode as the first
 * argument.
 *
 * Example: /usr/bin/php cron.php my-pass-code
 */
define('PASSCODE', '');

/**
 * Dont allow cron to be run via the browser, it must be run from CLI
 */
if(isset($_SERVER['HTTP_HOST']))
    exit(1);

if(!isset($argv[1]) || !strlen(PASSCODE) || PASSCODE !== $argv[1])
    exit("Missing or invalid pass code.\n");

define('CLI', true); // Put Caffeine into CLI mode
require('index.php');
Event::trigger('cron.run');
