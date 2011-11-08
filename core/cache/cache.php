<?php

class Cache extends Module {

    /**
     * Caches a string referenced by a given key. The key is turned into an md5 hash. Defaults to a 24
     * hour expire time (1440 minutes) which is set in the setup.php file via configs.
     *
     * @param string $key The key to associate with the cached data. Must be unique.
     * @param string $string The actual string to cache.
     * @param string $expire The expire time in minutes. Once the expire time is reached, the cahce is cleared.
     */
    public static function store($key, $string, $expire = null)
    {
        if(is_null($expire))
            $expire = Config::get('cache.default_expire_time');

        // TODO
    }

    /**
     * Gets a cached string based on its key.
     *
     * @param string $key The key of the cache to get.
     *
     * @return The cached string, if it exists. Otherwise boolean false is returned.
     */
    public static function get($key)
    {
        // TODO
    }

}
