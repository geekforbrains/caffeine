<?php
class Store_Model_Orders {

    public static function get_all()
    {
        Database::query('
            SELECT 
                so.*,
                sc.first_name,
                sc.last_name,
                c.created
            FROM {store_orders} so 
                LEFT JOIN {store_customers} sc ON sc.cid = so.customer_cid
                LEFT JOIN {content} c ON c.id = so.cid
            WHERE so.status != %s
            ORDER BY 
                c.created DESC
            ',
            'checkout'
        );

        return Database::fetch_all();
    }

    public static function get_by_status($status)
    {
        if($status == 'all')
            return self::get_all();

        Database::query('
            SELECT 
                so.*,
                sc.first_name,
                sc.last_name,
                c.created
            FROM {store_orders} so 
                LEFT JOIN {store_customers} sc ON sc.cid = so.customer_cid
                LEFT JOIN {content} c ON c.id = so.cid
            WHERE status = %s
            ORDER BY c.created DESC
            ', 
            $status
        );

        return Database::fetch_all();
    }

    public static function get_by_cid($cid)
    {
        Database::query('SELECT * FROM {store_orders} WHERE cid = %s', $cid);
        if(Database::num_rows() > 0)
            return Database::fetch_array();
        return false;
    }

    public static function create($customer_cid, $subtotal, $shipping, $tax, $total)
    {
        $cid = Content::create(STORE_TYPE_ORDER);
        $status = Database::insert('store_orders', array(
            'cid' => $cid,
            'customer_cid' => $customer_cid,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'total' => $total,
            'status' => 'checkout'
        ));

        if($status)
            return $cid;
        return false;
    }

    public static function add_product($order_cid, $product_cid, $qty, $price)
    {
        $cid = Content::create(STORE_TYPE_ORDER_PRODUCT);
        $status = Database::insert('store_order_products', array(
            'cid' => $cid,
            'order_cid' => $order_cid,
            'product_cid' => $product_cid,
            'qty' => $qty,
            'price' => $price
        ));

        if($status)
            return $cid;
        return false;
    }

    public static function add_product_option($order_product_cid, $order_cid, $product_cid, $option_type_cid, $option_cid)
    {
        return Database::insert('store_order_product_options', array(
            'order_product_cid' => $order_product_cid,
            'order_cid' => $order_cid,
            'product_cid' => $product_cid,
            'product_option_type_cid' => $option_type_cid,
            'product_option_cid' => $option_cid
        ));
    }

    public static function update_status($order_cid, $status)
    {
        return Database::update('store_orders',
            array('status' => $status),
            array('cid' => $order_cid)
        );
    }

    public static function get_products_by_order_cid($order_cid)
    {
        Database::query('
            SELECT
                sp.cid,
                sp.title,
                sp.slug,
                sp.sku,
                spo.cid AS order_product_cid,
                spo.qty,
                spo.price
            FROM {store_products} sp
                JOIN {store_order_products} spo ON spo.product_cid = sp.cid
            WHERE
                spo.order_cid = %s
            ',
            $order_cid
        );

        return Database::fetch_all();
    }

    public static function get_options_by_order_product_cid($order_product_cid)
    {
        Database::query('
            SELECT
                spo.*,
                spot.name AS type
            FROM {store_order_product_options} sopo
                JOIN {store_product_options} spo ON spo.cid = sopo.product_option_cid
                JOIN {store_product_option_types} spot ON spot.cid = sopo.product_option_type_cid
            WHERE
                sopo.order_product_cid = %s
            ',
            $order_product_cid
        );

        return Database::fetch_all();
    }

}
