<?php

class Store_Mailer {

    private static $_subjects = array(
        'new' => 'Your order receipt',
        'shipped' => 'Your order has been shipped'
    );

    private static $_send_to_admin = array('new'); // An array of statuses to send to admin

    /**
     * Sends an email to a customer based on the given order id's status.
     *
     * The status refelcts the html email file to load and send. An order with a status
     * of "new" would load the "new_order.php" block. A shipped order would load the "shipped_order.php" file.
     *
     * AN EMAIL WILL ONLY BE SENT IF THE VIEW EXISTS!!
     */
    public static function send($order_cid)
    {
        $order = Store_Model_Orders::get_by_cid($order_cid);

        $root = is_null(Caffeine::site_path()) ? CAFFEINE_ROOT : Caffeine::site_path();
        $view = sprintf('%smodules/store/blocks/emails/%s_order.php', $root, strtolower($order['status']));

        if(file_exists($view))
        {
            Debug::log('store', 'Sending email template: ' . $view);

            $products = Store_Model_Orders::get_products_by_order_cid($order_cid);
            $customer = Store_Model_Customers::get_by_cid($order['customer_cid']);

            $html = View::render($view, array(
                'order' => $order,
                'products' => $products,
                'customer' => $customer
            ));

            // Send email to customer
            Mailer::to($customer['email'], $customer['first_name'] . ' ' . $customer['last_name']);
            Mailer::from(STORE_EMAIL_FROM, STORE_EMAIL_FROM_NAME);
            Mailer::subject(self::$_subjects[$order['status']]);
            Mailer::body($html, true) // true = is html
            Mailer::send();

            // If admin wants to receive emails for this status, send it
            if(in_array($order['status'], self::$_send_to_admin) && strlen(STORE_EMAIL_ADMIN))
            {
                Mailer::to(STORE_EMAIL_ADMIN);
                Mailer::from(STORE_EMAIL_FROM, STORE_EMAIL_FROM_NAME);
                Mailer::subject(self::$_subjects[$order['status']]);
                Mailer::body($html, true) // true = is html
                Mailer::send();
            }
        }
    }

}
