<?php

/**
 * Handles PayPal Express Checkouts
 */
class Store_Express {

    /**
     * Redirects the user to the PayPal login/payment page.
     */
    public static function checkout()
    {
        if(!isset($_SESSION['store_cart']) || !$_SESSION['store_cart']['products'])
            Router::redirect('store/cart');

        if($_POST)
        {
            $check = array('required');
            Validate::check('first_name', 'First Name', $check);
            Validate::check('last_name', 'Last Name', $check);
            Validate::check('email', 'Email', array('required', 'valid_email'));
            Validate::check('address1', 'Address', $check);
            Validate::check('city', 'City', $check);
            Validate::check('shipping_state_cid', 'State/Province', $check);
            Validate::check('zip', 'Zip/Postal Code', $check);

            if(Validate::passed())
            {
                $_POST['shipping_country_cid'] = Store_Model_Shipping::get_country_cid_by_state_cid($_POST['shipping_state_cid']);
                if($customer_cid = Store_Model_Customers::create($_POST))
                {
                    // Create order
                    $order_cid = Store::create_order(
                        $customer_cid, 
                        $_POST['shipping_state_cid'], 
                        $_POST['shipping_country_cid']
                    );

                    // Need to get order for pricing info to send to paypal
                    $order = Store_Model_Orders::get_by_cid($order_cid);
                    $amount = $order['total'];
                    $currency = Store_Model_Settings::get('currency');

                    // Set express checkout data and redirect to paypal
                    $return_url = Router::full_url('store/express-checkout/finish/%d', $order_cid) . '/';
                    $cancel_url = Router::full_url('store/express-checkout/cancel/%d', $order_cid) . '/';

                    if(!PayPal::expressRedirect($amount, $currency, $return_url, $cancel_url))
                    {
                        Message::store(MSG_ERR, 'There was an error while doing PayPal Express Checkout. Please try again.');
                        Router::redirect('store/cart');
                    }
                }
                else
                    Message::set(MSG_ERR, 'Error creating customer. Please try again.');
            }
        }

        // Organize states by country
        $states = Store_Model_Shipping::get_states();

        $tmp = array();
        foreach($states as $s)
            $tmp[$s['country']][] = $s;
        $states = $tmp;

        View::load('Store', 'checkout_step1', array(
            'states' => $states
        ));
    }

    public static function finish($order_cid)
    {
        $response = PayPal::expressDetails();

        $payer_id = urldecode($response['PAYERID']);
        $amount = urldecode($response['AMT']);
        $currency = urldecode($response['CURRENCYCODE']);

        if($response = PayPal::expressProcess($payer_id, $amount, $currency))
        {
            Store_Model_Orders::update_status($order_cid, 'new');
            Store_Mailer::send($order_cid);

            // Clear order data from session
            if(isset($_SESSION['store_data']))
                unset($_SESSION['store_data']); 

            if(isset($_SESSION['store_cart']))
                unset($_SESSION['store_cart']);

            if(isset($_SESSION['store_order_cid']))
                unset($_SESSION['store_order_cid']);

            View::load('Store', 'checkout_finished', array(
                'data' => $response,
                'order' => Store_Model_Orders::get_by_cid($order_cid),
                'products' => Store_Model_Orders::get_products_by_order_cid($order_cid)
            ));

            return;
        }

        Message::store(MSG_ERR, 'There was an error processing your payment. Please try again.');
        Router::redirect('store/cart');
    }

    public static function cancel($order_cid)
    {
        if(isset($_SESSION['store_order_cid']))
            unset($_SESSION['store_order_cid']);

        Store_Model_Orders::delete($order_cid);

        Message::store(MSG_OK, 'Order cancelled.');
        Router::redirect('store/cart');
    }

}
