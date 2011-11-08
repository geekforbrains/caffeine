<?php

class Event extends Module {

    public static $_events = array();

    public static function getEvents() {
        return self::$_events;
    }

    public static function load($events)
    {
        foreach($events as $event => $callback)
        {
            if(!isset(self::$_events[$event]))
                self::$_events[$event] = array();

            self::$_events[$event][] = $callback;
        }
    }

    public static function trigger($event, $data = array(), $triggerCallback = null)
    {
        Dev::debug('event', 'Triggering: ' . $event);

        if(isset(self::$_events[$event]))
        {
            $eventCallbacks = self::$_events[$event];

            foreach($eventCallbacks as $eventCallback)
            {
                // Call the event callback and store any returned data
                $returnData = call_user_func_array($eventCallback, $data); 

                // If there is a trigger callback, send the return data 
                // from each event callback to the trigger
                if(!is_null($triggerCallback))
                {
                    Dev::debug('event', 'Calling back: ' . $triggerCallback[0] . '::' . $triggerCallback[1]);
                    call_user_func($triggerCallback, $returnData);
                }
            }
        }
    }

}
