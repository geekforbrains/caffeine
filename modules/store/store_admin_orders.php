<?php
class Store_Admin_Orders {

    public static function manage()
    {
        // Handle posts updating sort
        if($_POST)
            $_SESSION['store']['sort_orders'] = $_POST['sort'];

        // Sorting of orders is stored in session
        $sort = 'new';
        if(isset($_SESSION['store']['sort_orders']))
            $sort = $_SESSION['store']['sort_orders'];

        View::load('Store', 'admin/orders/manage', array(
            'sort' => $sort,
            'orders' => Store_Model_Orders::get_by_status($sort),
            'currency' => Store_Model_Settings::get('currency'),
            'symbol' => Store_Model_Settings::get('symbol')
        ));
    }

    public static function details($order_cid)
    {
        if($_POST)
        {
            Store_Model_Orders::update_status($order_cid, $_POST['status']);
            Message::set(MSG_OK, 'Status updated successfully.');
        }

        $order = Store_Model_Orders::get_by_cid($order_cid);
        $customer = Store_Model_Customers::get_by_cid($order['customer_cid']);
        $products = Store_Model_Orders::get_products_by_order_cid($order_cid);

        foreach($products as &$p)
        {
            $p['images'] = Store_Model_Products::get_photos_by_cid($p['cid']);
            //$p['options'] = Store_Model_Orders::get_options_by_order_product_cid($p['order_product_cid']);

            $options = Store_Model_Orders::get_options_by_order_product_cid($p['order_product_cid']);

            foreach($options as $o)
                if(strlen($o['sku']))
                    $p['sku'] = $o['sku'];

            $p['options'] = $options;
        }

        View::load('Store', 'admin/orders/details', array(
            'order' => $order,
            'customer' => $customer,
            'symbol' => Store_Model_Settings::get('symbol'),
            'currency' => Store_Model_Settings::get('currency'),
            'products' => $products
        ));
    }

}
