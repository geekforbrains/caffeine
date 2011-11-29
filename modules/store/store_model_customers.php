<?php
class Store_Model_Customers {

    public static function get_by_cid($cid)
    {
        Database::query('
            SELECT 
                sc.*,
                ssc.name AS country,
                sss.name AS state
            FROM {store_customers} sc
                JOIN {store_shipping_countries} ssc ON ssc.cid = sc.shipping_country_cid
                JOIN {store_shipping_states} sss ON sss.cid = sc.shipping_state_cid
            WHERE sc.cid = %s
            ', 
            $cid
        );

        if(Database::num_rows() > 0)
            return Database::fetch_array();
        return false;
    }

    public static function create($data)
    {
        $cid = Content::create(STORE_TYPE_CUSTOMER);
        $status = Database::insert('store_customers', array(
            'cid' => $cid,
            'shipping_country_cid' => $data['shipping_country_cid'],
            'shipping_state_cid' => $data['shipping_state_cid'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'address1' => $data['address1'],
            'address2' => $data['address2'],
            'city' => $data['city'],
            'zip' => $data['zip']
        ));

        if($status)
            return $cid;
        return false;
    }

}
