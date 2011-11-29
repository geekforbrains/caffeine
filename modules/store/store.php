<?php 
class Store {

    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
    public static function front()
    {
        View::load('Store', 'front', array(
            'recent' => Store_Model::get_recent_products()
        ));
    }

    /**
     * -------------------------------------------------------------------------
     * Added by shawn for a simple one page store
     * -------------------------------------------------------------------------
     */
    public static function all_products()
    {
		  	$products = Store_Model_Products::get_all();
		
			if($products)
			{
	        foreach($products as &$p)
	            $p['images'] = Store_Model_Products::get_photos_by_cid($p['cid']);
			}
			
        View::load('Store', 'products', array(
            'products' => $products
        ));
    }

    public static function featured_products() {
        return Store_Model_Products::get_featured();
    }

    /**
     * Show products based on posted search keywords
     */
    public static function search()
    {
        $results = array();

        if($_POST)
            $results = Store_Model::search($_POST['keywords']);
        else
            Router::redirect('/');

        View::load('Store', 'search', array(
            'results' => $results,
            'keywords' => htmlspecialchars(strip_tags($_POST['keywords']))
        ));
    }

    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
    public static function product_title($slug) {
        $product = Store_Model::get_product_by_slug($slug);
        return $product['title'];
    }

    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
    public static function product($slug)
    {
        if(!$product = Store_Model::get_product_by_slug($slug))
            return false; // 404

        View::load('Store', 'product', array(
            'currency' => Store_Model_Settings::get('currency'),
            'symbol' => Store_Model_Settings::get('symbol'),
            'product' => $product,
            'options' => Store_Model::get_product_options_by_cid($product['cid']),
            'categories' => Store_Model_Products::get_categories_by_cid($product['cid']),
            'files' => Store_Model_Products::get_files_by_cid($product['cid']),
            'related_products' => Store_Model_Products::get_related($product['cid'])
        ));
    }

    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
    public static function category_title($slug)
    {
        $category = Store_Model_Categories::get_by_slug($slug);
        return $category['name'];
    }

    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
    public static function category($slug)
    {
        if(!$category = Store_Model_Categories::get_by_slug($slug))
            Router::redirect('store');

        View::load('Store', 'category', array(
            'category' => $category,
            'parent_category' => Store_Model_Categories::get_by_cid($category['parent_cid']),
            'products' => Store_Model_Products::get_by_category_cid($category['cid']),
            'popular' => Store_Model_Products::get_popular_by_category_cid($category['cid'])
        ));
    }

    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
    public static function categories()
    {
        View::load('Store', 'categories', array(
            'categories' => Store_Model_Categories::get_all()
        ));
    }

    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
    public static function cart()
    {
        // If checking out via post button, redirect to checkout page
        if(isset($_POST['checkout']))
            Router::redirect('store/checkout');

        // If empty cart button was pressed
        if(isset($_POST['empty_cart']))
        {
            unset($_SESSION['store_cart']);
            Message::set(MSG_OK, 'Cart emptied successfully.');
        }

        // If cart hasn't been created, or was recently emptied, create empty cart in session
        if(!isset($_SESSION['store_cart']))
        {
            $_SESSION['store_cart'] = array(
                'products' => array(),
                'subtotal' => 0
            );
        }

        // Reference session for easier var settings
        $products =& $_SESSION['store_cart']['products'];
        $subtotal =& $_SESSION['store_cart']['subtotal'];

        if($_POST)
        {
            // When adding to cart from a product page
            if(isset($_POST['add_to_cart']))
            {
                // Check if product has already been added with the same options selected (if any)
                $key = self::_product_in_cart($products, $_POST);
                if($key !== false)
                    $products[$key]['qty'] += $_POST['qty'];

                // Else, add new product to cart
                else
                {
                    $product = Store_Model_Products::get_by_cid($_POST['product_cid']);
                    $product['images'] = Store_Model_Products::get_photos_by_cid($_POST['product_cid']);
                    $product = array_merge($product, $_POST);
                    
                    // If an option was selected, check to see if it has an override price
                    if(isset($_POST['options']) && $_POST['options'])
                    {
                        foreach($_POST['options'] as $type_cid => $option_cid)
                        {
                            $option = Store_Model_Products::get_value_by_cid($option_cid);
                            if($option['price'] > 0)
                                $product['price'] = $option['price'];

                            // Add details of product option to cart for display
                            $product['option_values'][] = $option;
                        }
                    }

                    $products[] = $product;
                }

                Message::set(MSG_OK, 'Product added to cart.');
            }

            // When updating cart from cart page
            if(isset($_POST['update_cart']) && isset($_POST['qty']))
            {
                // Update qtys based on new posted qtys
                foreach($_POST['qty'] as $k => $v)
                {
                    $value = intval($v);
                    if($value > 0)
                        $products[$k]['qty'] = intval($v);

                    // If the qty value is <= 0, remove the product
                    else
                        unset($products[$k]);
                }

                Message::set(MSG_OK, 'Cart updated successfully.');
            }

            // Update subtotal based on products and qty
            $subtotal = 0;
            foreach($products as $k => $p)
                $subtotal += ($p['price'] * $p['qty']);
        }

        View::load('Store', 'cart', array(
            'products' => $products,
            'currency' => Store_Model_Settings::get('currency'),
            'symbol' => Store_Model_Settings::get('symbol'),
            'subtotal' => $subtotal
        ));
    }

    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
    public static function checkout_step1()
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
                    $order_cid = self::_create_order(
                        $customer_cid, 
                        $_POST['shipping_state_cid'], 
                        $_POST['shipping_country_cid']
                    );

                    // Redirect to totals/payment page
                    $_SESSION['store_order_cid'] = $order_cid;
                    Router::redirect('store/checkout/step2');
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

    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
    public static function checkout_step2()
    {
        // Payment form was submitted
        if($_POST)
        {
            $_SESSION['store_data'] = self::_process_order($_POST);
            Router::redirect('store/checkout/finished');
        }
        
        // Get order info to display totals
        $order_cid = $_SESSION['store_order_cid'];
        $order = Store_Model_Orders::get_by_cid($order_cid);

        View::load('Store', 'checkout_step2', array(
            'order' => $order,
            'currency' => Store_Model_Settings::get('currency'),
            'symbol' => Store_Model_Settings::get('symbol')
        ));
    }

    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
    public static function checkout_finished()
    {
        $data = $_SESSION['store_data']['data'];
        $view = 'checkout_error';

        if($data['ACK'] == 'Success')
        {
            Store_Model_Orders::update_status($_SESSION['store_order_cid'], 'new');

            Store_Mailer::send($_SESSION['store_order_cid']);

            // Clear order data from session
            unset($_SESSION['store_data']); 
            unset($_SESSION['store_cart']);
            unset($_SESSION['store_order_cid']);

            // Set view to finish
            $view = 'checkout_finished';
        }

        View::load('Store', $view, array(
            'data' => $data
        ));
    }

    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
    private static function _create_order($customer_cid, $state_cid, $country_cid)
    {
        // Get subtotals like in cart using session
        $subtotal = $_SESSION['store_cart']['subtotal'];

        // Determine shipping prices for each product based on size
        $shipping = 0;
        $products = $_SESSION['store_cart']['products'];
        foreach($products as &$p)
        {
            $size_cid = Store_Model_Products::get_shipping_size_by_cid($p['product_cid']);

            // Check if province has an override size
            if(!$price = Store_Model_Shipping::get_state_shipping_price_by_cid($state_cid, $size_cid))

                // Check if country has an override size
                if(!$price = Store_Model_Shipping::get_country_shipping_price_by_cid($country_cid, $size_cid))

                    // Use default, global size price
                    $price = Store_Model_Shipping::get_shipping_price_by_cid($size_cid); 
                        
            $shipping += $price;
        }

        // Determine taxes
        $tax_rate = round(intval(Store_Model_Settings::get('tax')) / 100, 2);
        $country = Store_Model_Shipping::get_country_by_cid($country_cid);
        $state = Store_Model_Shipping::get_state_by_cid($state_cid);

        if($country['tax'] > 0)
            $tax_rate = round(intval($country['tax']) / 100, 2);

        if($state['tax'] > 0)
            $tax_rate = round(intval($state['tax']) / 100, 2);

        // Combine shipping and subtotal, then calc taxes
        $shipping = round($shipping, 2); // Round shipping first
        //$tax_rate = round(intval(Store_Model_Settings::get('tax')) / 100, 2);
        $tax = round(($subtotal + $shipping) * $tax_rate, 2);
        $total = round($subtotal + $shipping + $tax, 2);

        if($order_cid = Store_Model_Orders::create($customer_cid, $subtotal, $shipping, $tax, $total))
        {
            // Add products to order
            foreach($_SESSION['store_cart']['products'] as $p)
            {
                $order_product_cid = Store_Model_Orders::add_product($order_cid, $p['product_cid'], $p['qty'], $p['price']);
                
                if(!isset($p['options']) || !$order_product_cid) // If something bad happened with product, skip it
                    continue;

                // Add any options to the current product being ordered
                foreach($p['options'] as $option_type_cid => $option_cid)
                {
                    Store_Model_Orders::add_product_option(
                        $order_product_cid,
                        $order_cid,
                        $p['product_cid'],
                        $option_type_cid,
                        $option_cid
                    );
                }

            }

            return $order_cid;
        }

        return false;
    }

    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
    private static function _process_order($post)
    {
        // Process CC directly
        $method = 'DoDirectPayment';

        // Build data array for sending to PayPal
        $data = array(
            'IPADDRESS' => $_SERVER['REMOTE_ADDR'],

            // CC Info
            'CREDITCARDTYPE' => $post['cc_type'],
            'ACCT' => $post['cc_num'],
            'EXPDATE' => $post['cc_exp_month'] .''. $post['cc_exp_year'],
            'CVV2' => $post['cc_cvv2'],

            // Person Info
            'FIRSTNAME' => $post['first_name'],
            'LASTNAME' => $post['last_name'],
            'STREET' => $post['address'],
            'CITY' => $post['city'],
            'STATE' => $post['state'],
            'COUNTRYCODE' => $post['country'],
            'ZIP' => $post['zip'],

            // Amounts
            'AMT' => $post['total'],
            'CURRENCYCODE' => Store_Model_Settings::get('currency')
        );

        return PayPal::process($method, $data);
    }

    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
    private static function _product_in_cart($products, $post)
    {
        foreach($products as $key => $p)
        {
            if($p['product_cid'] == $post['product_cid'] && isset($p['options']) && $p['options'] == $post['options'])
                return $key;
        }

        return false;
    }

}
