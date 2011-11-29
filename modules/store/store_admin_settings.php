<?php
class Store_Admin_Settings {

    public static function general()
    {
        if($_POST)
        {
            foreach($_POST as $key => $value)
            {
                Store_Model_Settings::set($key, $value);
            }

            Message::set(MSG_OK, 'Settings updated successfully.');
        }
        
        View::load('Store', 'admin/settings/general', array(
            'currency' => Store_Model_Settings::get('currency'),
            'symbol' => Store_Model_Settings::get('symbol'),
            'tax' => Store_Model_Settings::get('tax')
        ));
    }

    public static function payments()
    {
        View::load('Store', 'admin/settings/payments');
    }

}
