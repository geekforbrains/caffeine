<?php
class Store_Model_Settings {

    /**
     * -------------------------------------------------------------------------
     * Gets a setting value, returns the value if it exists, otherwise boolean
     * false is returned.
     * -------------------------------------------------------------------------
     */
    public static function get($setting)
    {
        Database::query('SELECT value FROM {store_settings} WHERE setting = %s', $setting);

        if(Database::num_rows() > 0)
            return Database::fetch_single('value');
        return false;
    }

    /**
     * -------------------------------------------------------------------------
     * Updates or creates a new setting. Yep...
     * -------------------------------------------------------------------------
     */
    public static function set($setting, $value)
    {
        // Check if the setting exists, if it does, do an update
        if(self::get($setting))
        {
            Database::update('store_settings', 
                array('value' => $value),
                array('setting' => $setting)
            );
        }

        // Setting doens't exist, create it
        else
        {
            Database::insert('store_settings', array(
                'setting' => $setting,
                'value' => $value
            ));
        }
    }

}
