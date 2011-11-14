<?php

class Cache extends Module {

    /**
     * Caches a string referenced by a given key. The key is turned into an md5 hash. Defaults to a 24
     * hour expire time (1440 minutes) which is set in the setup.php file via configs.
     *
     * @param string $key The key to associate with the cached data. Must be unique.
     * @param string $data The actual string of data to cache.
     * @param string $expire The expire time in any strtotime supported format.
     */
    public static function store($key, $data, $expire = null)
    {
        if(is_null($expire))
            $expire = Config::get('cache.default_expire_time');

        $keyHash = md5($key);

        if(!Cache::cache()->where('key_hash', '=', $keyHash)->first())
        {
            return Cache::cache()->insert(array(
                'key_hash' => $keyHash,
                'data' => $data,
                'expires_on' => strtotime($expire)
            ));
        }
        else
        {
            return Cache::cache()->where('key_hash', '=', $keyHash)->update(array(
                'data' => $data,
                'expires_on' => strtotime($expire)
            ));
        }
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
        if($cache = Cache::cache()->where('key_hash', '=', md5($key))->first())
            return $cache->data;
        return false;
    }

    /**
     * Clears cached data based on the given key.
     */
    public static function clear($key) {
        return Cache::cache()->where('key_hash', '=', md5($key))->delete();
    }

    // TODO Clear expired caches, this method is called via the cron.run event
    public static function clearExpired() {

    }

}
