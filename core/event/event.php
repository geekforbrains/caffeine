<?php

class Event extends Module {

    /**
     * Stores events loaded from other modules setup.php files.
     */
    public static $_events = array();

    /**
     * Gets all events loaded from other modules setup.php files.
     *
     * @return array Array of events
     */
    public static function getEvents() {
        return self::$_events;
    }

    /**
     * Loads events set in other modules setup.php files. This method
     * is typically called via the Load module and should not be called
     * directly.
     *
     * @param array $events An array of events to load
     */
    public static function load($events)
    {
        foreach($events as $event => $callback)
        {
            if(!isset(self::$_events[$event]))
                self::$_events[$event] = array();

            self::$_events[$event][] = $callback;
        }
    }

    /**
     * Calls (triggers) an event by calling all callbacks associated with it. Data can optionally
     * be sent to each callback method in the form of an array. An optional trigger callback method can
     * be defined with will be called after each successful event callback.
     *
     * Basic Example:
     * Trigger::event('somemodule.myevent');
     *
     * Data Example:
     * Trigger::event('somemodule.myevent', array('color' => 'blue', 'length' => 255));
     *
     * Trigger Callback Example:
     * Trigger::event('somemodule.myevent', null, array('MyClass', 'myStaticMethod'));
     *
     * @param string $event The event name to trigger callbacks for
     * @param array $data An optional array of data to be sent to each callback
     * @param string $triggerCallback an optional method to call after each callback, is passed any data returned by each callback
     */
    public static function trigger($event, $data = null, $triggerCallback = null)
    {
        if(is_null($data))
            $data = array(); // To be compatible with call_user_func_array

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
                    Log::debug('event', 'Calling back: ' . $triggerCallback[0] . '::' . $triggerCallback[1]);
                    call_user_func($triggerCallback, $returnData);
                }
            }
        }
    }

}
