<?php
class Store_Admin_Shipping {

    /**
     * -------------------------------------------------------------------------
     * Manage shipping Countries and State/Provinces. These are the locations
     * that will be available to ship to when placing an order.
     * 
     * These locations are also used to override the global shipping prices 
     * based on sizes.
     * -------------------------------------------------------------------------
     */
    public static function locations()
    {
        if($_POST)
        {
            // Adding Country
            if(isset($_POST['add_country']))
            {
                Validate::check('country', 'Country', array('required'));

                if(Validate::passed())
                {
                    if(!Store_Model_Shipping::country_exists($_POST['country']))
                    {
                        if(Store_Model_Shipping::add_country($_POST['country']))
                            Message::set(MSG_OK, 'Country created successfully.');
                        else
                            Message::set(MSG_ERR, 'Error creating country. Please try again.');
                    }
                    else
                        Message::set(MSG_ERR, 'That Country already exists.');
                }
            }

            // Adding State/Province
            if(isset($_POST['add_state']))
            {
                Validate::check('country_cid', 'Country', array('required'));
                Validate::check('state', 'State/Province', array('required'));  

                if(Validate::passed())
                {
                    if(!Store_Model_Shipping::state_exists($_POST['state']))
                    {
                        if(Store_Model_Shipping::add_state($_POST['country_cid'], $_POST['state']))
                            Message::set(MSG_OK, 'State/Province created successfully.');
                        else
                            Message::set(MSG_ERR, 'Error creating state/province. Please try again.');
                    }
                    else
                        Message::set(MSG_ERR, 'That State/Province already exists.');
                }
            }
        }

        View::load('Store', 'admin/shipping/locations', array(
            'countries' => Store_Model_Shipping::get_countries(),
            'states' => Store_Model_Shipping::get_states()
        ));
    }

    /**
     * -------------------------------------------------------------------------
     * Sizes are you used to categorize different sized products with different
     * shipping prices. The sizes have default pricing that can overriden based
     * on Country or State/Province.
     * -------------------------------------------------------------------------
     */
    public static function sizes()
    {
        if($_POST)
        {
            Validate::check('size', 'Size', array('required'));
            Validate::check('price', 'Price', array('required'));

            if(Validate::passed())
            {
                if(!Store_Model_Shipping::size_exists($_POST['size']))
                {
                    if(Store_Model_Shipping::add_size($_POST['size'], $_POST['price']))
                        Message::set(MSG_OK, 'Size created successfully.');
                    else
                        Message::set(MSG_ERR, 'Error creating size. Please try again.');
                }
                else
                    Message::set(MSG_ERR, 'That size already exists.');
            }
        }

        View::load('Store', 'admin/shipping/sizes', array(
            'sizes' => Store_Model_Shipping::get_sizes()
        ));
    }

    /**
     * -------------------------------------------------------------------------
     * View for editing individual shipping sizes.
     * -------------------------------------------------------------------------
     */
    public static function edit_size($cid)
    {
        if(!$size = Store_Model_Shipping::get_size_by_cid($cid))
            Router::redirect('admin/store/shipping/sizes');

        if($_POST)
        {
            Validate::check('size', 'Size', array('required'));
            Validate::check('price', 'Price', array('required'));

            if(Validate::passed())
            {
                if(Store_Model_Shipping::update_size($cid, $_POST['size'], $_POST['price']))
                {
                    Message::store(MSG_OK, 'Size updated successfully.');
                    Router::redirect('admin/store/shipping/sizes');
                }
                else
                    Message::set(MSG_ERR, 'Error updating size. Please try again.');
            }
        }

        View::load('Store', 'admin/shipping/edit_size', array(
            'size' => $size
        ));
    }

    /**
     * -------------------------------------------------------------------------
     * Displays a page for editing the country name as well as specifying
     * override shipping sizes.
     * -------------------------------------------------------------------------
     */
    public static function edit_country($cid)
    {
        if(!$country = Store_Model_Shipping::get_country_by_cid($cid))
            Router::redirect('admin/store/shipping/locations');

        if($_POST)
        {
            // Editing Country
            if(isset($_POST['edit_country']))
            {
                Validate::check('name', 'Country', array('required'));

                if(Validate::passed())
                {
                    if(Store_Model_Shipping::update_country($cid, $_POST))
                    {
                        $country = Store_Model_Shipping::get_country_by_cid($cid);
                        Message::set(MSG_OK, 'Country updated successfully.');
                    }
                    else
                        Message::set(MSG_ERR, 'Error updating country. Please try again.');
                }
            }


            // Adding Override Size
            if(isset($_POST['add_size']))
            {
                if(!Store_Model_Shipping::country_size_exists($cid, $_POST['size_cid']))
                {
                    if(Store_Model_Shipping::add_country_size($cid, $_POST['size_cid']))
                        Message::set(MSG_OK, 'Size added successfully.');
                    else
                        Message::set(MSG_ERR, 'Error adding size. Please try again.');
                }
                else
                    Message::set(MSG_ERR, 'That size has already been added.');
            }

            // Updating Overriden Sizes
            if(isset($_POST['update_sizes']))
            {
                unset($_POST['update_sizes']);

                foreach($_POST as $size_cid => $value)
                    Store_Model_Shipping::update_country_size($cid, ltrim($size_cid, 'cid'), $value);

                Message::set(MSG_OK, 'Sizes updated successfully.');
            }
        }

        View::load('Store', 'admin/shipping/edit_country', array(
            'country' => $country,
            'country_sizes' => Store_Model_Shipping::get_country_sizes($cid),
            'sizes' => Store_Model_Shipping::get_sizes()
        ));
    }

    /**
     * -------------------------------------------------------------------------
     * Displays a page for editing the state name or country it belongs to as
     * well as specifying override shipping sizes. 
     * -------------------------------------------------------------------------
     */
    public static function edit_state($cid)
    {
        if($_POST)
        {
            // Updating state
            if(isset($_POST['update_state']))
            {
                if(Store_Model_Shipping::update_state($cid, $_POST))
                    Message::set(MSG_OK, 'State updated successfully.');
                else
                    Message::set(MSG_ERR, 'Error updating state. Please try again.');
            }

            // Adding Override Size
            if(isset($_POST['add_size']))
            {
                if(!Store_Model_Shipping::state_size_exists($cid, $_POST['size_cid']))
                {
                    if(Store_Model_Shipping::add_state_size($cid, $_POST['size_cid']))
                        Message::set(MSG_OK, 'Size added successfully.');
                    else
                        Message::set(MSG_ERR, 'Error adding size. Please try again.');
                }
                else
                    Message::set(MSG_ERR, 'That size has already been added.');
            }

            // Updating Overriden Sizes
            if(isset($_POST['update_sizes']))
            {
                unset($_POST['update_sizes']);
                foreach($_POST as $size_cid => $value)
                    Store_Model_Shipping::update_state_size($cid, ltrim($size_cid, 'cid'), $value);

                Message::set(MSG_OK, 'Sizes updated successfully.');
            }
        }

        View::load('Store', 'admin/shipping/edit_state', array(
            'state' => Store_Model_Shipping::get_state_by_cid($cid),
            'countries' => Store_Model_Shipping::get_countries(),
            'state_sizes' => Store_Model_Shipping::get_state_sizes($cid),
            'sizes' => Store_Model_Shipping::get_sizes()
        ));
    }

    public static function delete_size($cid)
    {
        if(Store_Model_Shipping::delete_size($cid))
            Message::store(MSG_OK, 'Size deleted successfully.');
        else
            Message::store(MSG_ERR, 'Error deleting size. Please try again.');

        Router::redirect('admin/store/shipping/sizes');
    }

    public static function delete_country_size($country_cid, $size_cid)
    {
        if(Store_Model_Shipping::delete_country_size($country_cid, $size_cid))
            Message::store(MSG_OK, 'Size deleted successfully.');
        else
            Message::store(MSG_ERr, 'Error deleting size. Please try again.');

        Router::redirect('admin/store/shipping/edit-country/' . $country_cid);
    }

    public static function delete_country($cid)
    {
        if(Store_Model_Shipping::delete_country($cid))
            Message::store(MSG_OK, 'Country deleted successfully.');
        else
            Message::store(MSG_ERR, 'Error deleting country. Please try again.');

        Router::redirect('admin/store/locations');
    }

}
