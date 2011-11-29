<?php
class Store_Model_Shipping {

    public static function get_countries()
    {
        Database::query('SELECT * FROM {store_shipping_countries} ORDER BY name ASC');
        return Database::fetch_all();
    }

    public static function get_states()
    {
        Database::query('
            SELECT
                sss.*,
                ssc.name AS country
            FROM {store_shipping_states} sss
                LEFT JOIN {store_shipping_countries} ssc ON ssc.cid = sss.shipping_country_cid
            ORDER BY
                ssc.name,
                sss.name
            ASC
        ');

        return Database::fetch_all();
    }

    public static function get_sizes()
    {
        Database::query('SELECT * FROM {store_shipping_sizes} ORDER BY price ASC');
        return Database::fetch_all();
    }

    public static function get_country_sizes($cid)
    {
        Database::query('
            SELECT
                sscs.*,
                sss.size
            FROM {store_shipping_country_sizes} sscs
                LEFT JOIN {store_shipping_sizes} sss ON sss.cid = sscs.shipping_size_cid
            WHERE
                sscs.shipping_country_cid = %s
            ',
            $cid
        );

        return Database::fetch_all();
    }

    public static function get_state_sizes($cid)
    {
        Database::query('
            SELECT
                ssss.*,
                sss.size
            FROM {store_shipping_state_sizes} ssss
                LEFT JOIN {store_shipping_sizes} sss ON sss.cid = ssss.shipping_size_cid
            WHERE
                ssss.shipping_state_cid = %s
            ',
            $cid
        );

        return Database::fetch_all();
    }

    public static function get_country_by_cid($cid)
    {
        Database::query('SELECT * FROM {store_shipping_countries} WHERE cid = %s', $cid);
        if(Database::num_rows() > 0)
            return Database::fetch_array();
        return false;
    }

    public static function get_state_by_cid($cid)
    {
        Database::query('SELECT * FROM {store_shipping_states} WHERE cid = %s', $cid);
        if(Database::num_rows() > 0)
            return Database::fetch_array(); 
        return false;
    }

    public static function get_size_by_cid($cid)
    {
        Database::query('SELECT * FROM {store_shipping_sizes} WHERE cid = %s', $cid);
        if(Database::num_rows() > 0)
            return Database::fetch_array();
        return false;
    }

    public static function get_country_cid_by_state_cid($state_cid)
    {
        Database::query('SELECT shipping_country_cid FROM {store_shipping_states} WHERE cid = %s', $state_cid);
        if(Database::num_rows() > 0)
            return Database::fetch_single('shipping_country_cid');
        return false;
    }

    public static function add_country($country)
    {
        $cid = Content::create(STORE_TYPE_COUNTRY);
        return Database::insert('store_shipping_countries', array(
            'cid' => $cid,
            'name' => $country
        ));
    }

    public static function add_state($country_cid, $state)
    {
        $cid = Content::create(STORE_TYPE_STATE);
        return Database::insert('store_shipping_states', array(
            'cid' => $cid,
            'shipping_country_cid' => $country_cid,
            'name' => $state
        ));
    }

    public static function add_size($size, $price)
    {
        $cid = Content::create(STORE_TYPE_SIZE);
        return Database::insert('store_shipping_sizes', array(
            'cid' => $cid,
            'size' => $size,
            'price' => $price
        ));
    }

    public static function add_country_size($country_cid, $size_cid)
    {
        return Database::insert('store_shipping_country_sizes', array(
            'shipping_country_cid' => $country_cid,
            'shipping_size_cid' => $size_cid,
            'price' => 0.00
        ));
    }

    public static function add_state_size($state_cid, $size_cid)
    {
        return Database::insert('store_shipping_state_sizes', array(
            'shipping_state_cid' => $state_cid,
            'shipping_size_cid' => $size_cid,
            'price' => 0.00
        ));
    }

    public static function update_size($cid, $size, $price)
    {
        Content::update($cid);
        Database::update('store_shipping_sizes', 
            array(
                'size' => $size,
                'price' => $price
            ),
            array('cid' => $cid)
        );

        if(Database::affected_rows() > 0)
            return true;
        return false;
    }

    public static function update_country_size($country_cid, $size_cid, $price)
    {
        Database::update('store_shipping_country_sizes', 
            array(
                'price' => $price
            ),
            array(
                'shipping_country_cid' => $country_cid,
                'shipping_size_cid' => $size_cid
            )
        );

        if(Database::affected_rows() > 0)
            return true;
        return false;
    }

    public static function update_state_size($state_cid, $size_cid, $price)
    {
        Database::update('store_shipping_state_sizes', 
            array(
                'price' => $price
            ),
            array(
                'shipping_state_cid' => $state_cid,
                'shipping_size_cid' => $size_cid
            )
        );

        if(Database::affected_rows() > 0)
            return true;
        return false;
    }

    public static function update_country($cid, $data)
    {
        Content::update($cid);
        Database::update('store_shipping_countries',
            array(
                'name' => $data['name'],
                'tax' => $data['tax']
            ),
            array('cid' => $cid)
        );

        if(Database::affected_rows() > 0)
            return true;
        return false;
    }

    public static function update_state($cid, $data)
    {
        Content::update($cid);
        return Database::update('store_shipping_states',
            array(
                'shipping_country_cid' => $data['shipping_country_cid'],
                'name' => $data['name'],
                'tax' => $data['tax']
            ),
            array('cid' => $cid)
        );
    }

    public static function delete_size($cid)
    {
        Content::delete($cid);
        return Database::delete('store_shipping_sizes', array('cid' => $cid));
    }

    public static function delete_country_size($country_cid, $size_cid)
    {
        Database::delete('store_shipping_country_sizes', array(
            'shipping_country_cid' => $country_cid,
            'shipping_size_cid' => $size_cid
        ));

        if(Database::affected_rows() > 0)
            return true;
        return false;
    }

    public static function country_exists($country)
    {
        Database::query('SELECT cid FROM {store_shipping_countries} WHERE name LIKE %s', $country);
        if(Database::num_rows() > 0)
            return true;
        return false;
    }

    public static function state_exists($state)
    {
        Database::query('SELECT cid FROM {store_shipping_states} WHERE name LIKE %s', $state); 
        if(Database::num_rows() > 0)
            return true;
        return false;
    }

    public static function size_exists($size)
    {
        Database::query('SELECT cid FROM {store_shipping_sizes} WHERE size LIKE %s', $size);
        if(Database::num_rows() > 0)
            return true;
        return false;
    }

    public static function country_size_exists($country_cid, $size_cid)
    {
        Database::query('SELECT * FROM {store_shipping_country_sizes} WHERE
            shipping_country_cid = %s AND shipping_size_cid = %s', $country_cid, $size_cid);

        if(Database::num_rows() > 0)
            return true;
        return false;
    }

    public static function state_size_exists($state_cid, $size_cid)
    {
        Database::query('SELECT * FROM {store_shipping_state_sizes} WHERE
            shipping_state_cid = %s AND shipping_size_cid = %s', $state_cid, $size_cid);

        if(Database::num_rows() > 0)
            return true;
        return false;
    }

    public static function get_state_shipping_price_by_cid($state_cid, $size_cid)
    {
        Database::query('SELECT price FROM {store_shipping_state_sizes} WHERE 
            shipping_state_cid = %s AND shipping_size_cid = %s', $state_cid, $size_cid);

        if(Database::num_rows() > 0)
            return Database::fetch_single('price');
        return false;
    }

    public static function get_country_shipping_price_by_cid($country_cid, $size_cid)
    {
        Database::query('SELECT price FROM {store_shipping_country_sizes} WHERE
            shipping_country_cid = %s AND shipping_size_cid = %s', $country_cid, $size_cid);

        if(Database::num_rows() > 0)
            return Database::fetch_single('price');
        return false;
    }

    public static function get_shipping_price_by_cid($size_cid)
    {
        Database::query('SELECT price FROM {store_shipping_sizes} WHERE cid = %s', $size_cid);

        if(Database::num_rows() > 0)
            return Database::fetch_single('price');
        return false;
    }

    public static function delete_country($cid)
    {
        Content::delete($cid);
        return Database::delete('store_shipping_countries', array('cid' => $cid));
    }

}
