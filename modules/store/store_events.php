<?php
final class Store_Events {

    /**
     * ------------------------------------------------------------------------
     * Implements the Path::callbacks event.
     * ------------------------------------------------------------------------
     */
    public static function path_callbacks()
    {
        return array(
            // Front
            'store' => array(
                'title' => 'Store',
                'callback' => array('Store', 'front'),
                'auth' => true
            ),
            'store/search' => array(
                'title' => 'Search Results',
                'callback' => array('Store', 'search'),
                'auth' => true
            ),
            'store/product/%' => array(
                'title' => 'Product',
                'title_callback' => array('Store', 'product_title'),
                'callback' => array('Store', 'product'),
                'auth' => true
            ),
            'store/category/%' => array(
                'title' => 'Category',
                'title_callback' => array('Store', 'category_title'),
                'callback' => array('Store', 'category'),
                'auth' => true
            ),
            'store/cart' => array(
                'title' => 'My Cart',
                'callback' => array('Store', 'cart'),
                'auth' => true
            ),
            'store/checkout' => array(
                'title' => 'Checkout',
                'callback' => array('Store', 'checkout_step1'),
                'visible' => false,
                'auth' => true
            ),
            'store/checkout/step2' => array(
                'title' => 'Checkout',
                'callback' => array('Store', 'checkout_step2'),
                'visible' => false,
                'auth' => true
            ),
            'store/checkout/finished' => array(
                'title' => 'Checkout',
                'callback' => array('Store', 'checkout_finished'),
                'visible' => false,
                'auth' => true
            ),

            // Admin
            'admin/store' => array(
                'title' => 'Store',
                'alias' => 'admin/store/orders/manage'
            ),

            // Admin Orders
            'admin/store/orders' => array(
                'title' => 'Orders',
                'alias' => 'admin/store/orders/manage'
            ),
            'admin/store/orders/manage' => array(
                'title' => 'Manage',
                'callback' => array('Store_Admin_Orders', 'manage'),
                'auth' => 'manage orders'
            ),
            'admin/store/orders/details/%d' => array(
                'title' => 'Details',
                'callback' => array('Store_Admin_Orders', 'details'),
                'auth' => 'view order details',
                'visible' => false
            ),

            // Admin Products
            'admin/store/products' => array(
                'title' => 'Products',
                'alias' => 'admin/store/products/manage',
            ),
            'admin/store/products/manage' => array(
                'title' => 'Manage',
                'callback' => array('Store_Admin_Products', 'manage'),
                'auth' => 'manage products'
            ),
            'admin/store/products/create' => array(
                'title' => 'Create',
                'callback' => array('Store_Admin_Products', 'create'),
                'auth' => 'create products'
            ),
            'admin/store/products/edit/%d' => array(
                'callback' => array('Store_Admin_Products', 'edit'),
                'auth' => 'edit products',
                'visible' => false
            ),
            'admin/store/products/edit/%d/edit-option/%d' => array(
                'callback' => array('Store_Admin_Products', 'edit_option'),
                'auth' => 'edit product options',
                'visible' => false,
            ),
            'admin/store/products/edit/%d/edit-option/%d/delete-value/%d' => array(
                'callback' => array('Store_Admin_Products', 'delete_value'),
                'auth' => 'delete option values',
                'visible' => false
            ),
            'admin/store/products/edit/%d/delete-option/%d' => array(
                'callback' => array('Store_Admin_Products', 'delete_option'),
                'auth' => 'delete product option',
                'visible' => false
            ),
            'admin/store/products/edit/%d/delete-photo/%d' => array(
                'callback' => array('Store_Admin_Products', 'delete_photo'),
                'auth' => 'delete product photo',
                'visible' => false
            ),
            'admin/store/products/edit/%d/delete-file/%d' => array(
                'callback' => array('Store_Admin_Products', 'delete_file'),
                'auth' => 'delete product file',
                'visible' => false
            ),
            'admin/store/products/edit/%d/delete-related/%d' => array(
                'callback' => array('Store_Admin_Products', 'delete_related'),
                'auth' => 'deleted related products',
                'visible' => false
            ),
            'admin/store/products/delete/%d' => array(
                'callback' => array('Store_Admin_Products', 'delete'),
                'auth' => 'delete products',
                'visible' => false
            ),

            // Admin Categories
            'admin/store/categories' => array(
                'title' => 'Categories',
                'alias' => 'admin/store/categories/manage'
            ),
            'admin/store/categories/manage' => array(
                'title' => 'Manage',
                'callback' => array('Store_Admin_Categories', 'manage'),
                'auth' => 'manage categories'
            ),
            'admin/store/categories/edit/%d' => array(
                'callback' => array('Store_Admin_Categories', 'edit'),
                'auth' => 'edit categories',
                'visible' => false
            ),
            'admin/store/categories/delete/%d' => array(
                'callback' => array('Store_Admin_Categories', 'delete'),
                'auth' => 'delete categories',
                'visible' => false
            ),

            // Admin Shipping
            'admin/store/shipping' => array(
                'title' => 'Shipping',
                'alias' => 'admin/store/shipping/sizes'
            ),
            'admin/store/locations' => array(
                'title' => 'Locations',
                'callback' => array('Store_Admin_Shipping', 'locations'),
                'auth' => 'manage shipping locations'
            ),
            /*
            'admin/store/shipping/locations' => array(
                'title' => 'Locations',
                'callback' => array('Store_Admin_Shipping', 'locations'),
                'auth' => 'manage shipping locations'
            ),
            */
            'admin/store/shipping/sizes' => array(
                'title' => 'Sizes',
                'callback' => array('Store_Admin_Shipping', 'sizes'),
                'auth' => 'manage shipping sizes'
            ),
            'admin/store/shipping/sizes/edit/%d' => array(
                'callback' => array('Store_Admin_Shipping', 'edit_size'),
                'auth' => 'edit sizes'
            ),
            'admin/store/shipping/sizes/delete/%d' => array(
                'callback' => array('Store_Admin_Shipping', 'delete_size'),
                'auth' => 'delete sizes'
            ),
            'admin/store/shipping/edit-country/%d' => array(
                'callback' => array('Store_Admin_Shipping', 'edit_country'),
                'auth' => 'edit country',
                'visible' => false
            ),
            'admin/store/shipping/edit-country/%d/delete-size/%d' => array(
                'callback' => array('Store_Admin_Shipping', 'delete_country_size'),
                'auth' => 'delete country size',
                'visible' => false
            ),
            'admin/store/shipping/delete-country/%d' => array(
                'callback' => array('Store_Admin_Shipping', 'delete_country'),
                'auth' => 'delete countries',
                'visible' => false
            ),
            'admin/store/shipping/edit-state/%d' => array(
                'callback' => array('Store_Admin_Shipping', 'edit_state'),
                'auth' => 'edit state',
                'visible' => false
            ),

            // Admin Settings
            'admin/store/settings' => array(
                'title' => 'Settings',
                'alias' => 'admin/store/settings/general'
            ),
            'admin/store/settings/general' => array(
                'title' => 'General',
                'callback' => array('Store_Admin_Settings', 'general'),
                'auth' => 'manage general settings'
            ),
            /*
            TODO
            'admin/store/settings/payments' => array(
                'title' => 'Payments',
                'callback' => array('Store_Admin_Settings', 'payments'),
                'auth' => 'manage payment settings'
            )
            */
        );
    }

    /**
     * ------------------------------------------------------------------------
     * Implements the Database::install event.
     * ------------------------------------------------------------------------
     */
    public static function database_install()
    {
        return array(
            'store_products' => array(
                'fields' => array(
                    'cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'shipping_size_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'title' => array(
                        'type' => 'varchar',
                        'length' => 255, 
                        'not null' => true  
                    ),
                    'slug' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    ),
                    'blurb' => array(
                        'type' => 'varchar',
                        'length' => '255',
                        'not null' => true
                    ),
                    'short_description' => array(
                        'type' => 'text',
                        'size' => 'normal',
                        'not null' => true
                    ),
                    'long_description' => array(
                        'type' => 'text',
                        'size' => 'big',
                        'not null' => true
                    ),
                    'sku' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    ),
                    'price' => array(
                        'type' => 'double',
                        'not null' => true
                    ),
                    'deleted' => array( // Used to track deleted products that have already been ordered
                        'type' => 'int', // and therefore should not be deleted from the db
                        'size' => 'tiny',
                        'not null' => true
                    ),
                    'is_featured' => array(
                        'type' => 'int',
                        'size' => 'tiny',
                        'not null' => true
                    ),
                    'is_used' => array(
                        'type' => 'int',
                        'size' => 'tiny',
                        'not null' => true
                    )
                ),

                'indexes' => array(
                    'shipping_size_cid' => array('shipping_size_cid'),
                    'slug' => array('slug'),
                    'price' => array('price'),
                    'deleted' => array('deleted'),
                    'is_featured' => array('is_featured'),
                    'is_used' => array('is_used')
                ),

                'primary key' => array('cid')
            ),

            'store_product_categories' => array(
                'fields' => array(
                    'product_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'category_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    )
                ),

                'indexes' => array(
                    'product_cid' => array('product_cid'),
                    'category_cid' => array('category_cid')
                )
            ),

            'store_product_photos' => array(
                'fields' => array(
                    'product_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'media_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    )
                ),

                'indexes' => array(
                    'product_cid' => array('product_cid'),
                    'media_cid' => array('media_cid')
                )
            ),

            'store_product_files' => array(
                'fields' => array(
                    'product_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'media_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    )
                ),

                'indexes' => array(
                    'product_cid' => array('product_cid'),
                    'media_cid' => array('media_cid')

                )
            ),

            'store_product_option_types' => array(
                'fields' => array(
                    'cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'product_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'name' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null'
                    )
                ),

                'indexes' => array(
                    'product_cid' => array('product_cid'),
                ),

                'primary key' => array('cid')
            ),

            'store_product_options' => array(
                'fields' => array(
                    'cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'product_option_type_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'value' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    ),
                    'price' => array(
                        'type' => 'double',
                        'size' => 'normal',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'sku' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    )
                ),

                'indexes' => array(
                    'product_option_type_cid' => array('product_option_type_cid')
                ),

                'primary key' => array('cid')
            ),

            'store_categories' => array(
                'fields' => array(
                    'cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'parent_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'name' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    ),
                    'slug' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    )
                ),

                'indexes' => array(
                    'parent_cid' => array('parent_cid'),
                    'name' => array('name'),
                    'slug' => array('slug')
                ),

                'primary key' => array('cid')
            ),

            'store_orders' => array(
                'fields' => array(
                    'cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'customer_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'subtotal' => array(
                        'type' => 'double',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'shipping' => array(
                        'type' => 'double',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'tax' => array(
                        'type' => 'double',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'total' => array(
                        'type' => 'double',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'status' => array( // checkout, pending, paid, shipped, cancelled
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    )
                ),

                'indexes' => array(
                    'customer_cid' => array('customer_cid'),
                    'subtotal' => array('subtotal'),
                    'shipping' => array('shipping'),
                    'tax' => array('tax'),
                    'total' => array('total')
                ),

                'primary key' => array('cid')
            ),

            'store_customers' => array(
                'fields' => array(
                    'cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'shipping_country_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'shipping_state_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'first_name' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    ),
                    'last_name' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    ),
                    'email' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    ),
                    'address1' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    ),
                    'address2' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    ),
                    'city' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    ),
                    'zip' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    ),
                ),

                'primary key' => array('cid')
            ),

            'store_order_products' => array(
                'fields' => array(
                    'cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'order_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'product_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'qty' => array(
                        'type' => 'int',
                        'size' => 'normal',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'price' => array(
                        'type' => 'double',
                        'size' => 'normal',
                        'unsigned' => true,
                        'not null' => true
                    )
                ),

                'indexes' => array(
                    'cid' => array('cid'),
                    'order_cid' => array('order_cid'),
                    'product_cid' => array('product_cid'),
                    'qty' => array('qty')
                )
            ),

            'store_order_product_options' => array(
                'fields' => array(
                    'order_product_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'order_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'product_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'product_option_type_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'product_option_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    )
                ),

                'indexes' => array(
                    'order_product_cid' => array('order_product_cid'),
                    'order_cid' => array('order_cid'),
                    'product_cid' => array('product_cid'),
                    'product_option_type_cid' => array('product_option_type_cid'),
                    'product_option_cid' => array('product_option_cid')
                )
            ),

            'store_shipping_sizes' => array(
                'fields' => array(
                    'cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'size' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    ),
                    'price' => array(
                        'type' => 'double',
                        'unsigned' => true,
                        'not null' => true
                    )
                ),

                'primary key' => array('cid')
            ),

            'store_shipping_country_sizes' => array(
                'fields' => array(
                    'shipping_country_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'shipping_size_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'price' => array(
                        'type' => 'double',
                        'unsigned' => true,
                        'not null' => true
                    )
                ),

                'indexes' => array(
                    'shipping_country_cid' => array('shipping_country_cid'),
                    'shipping_size_cid' => array('shipping_size_cid')
                )
            ),

            'store_shipping_state_sizes' => array(
                'fields' => array(
                    'shipping_state_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'shipping_size_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'price' => array(
                        'type' => 'double',
                        'unsigned' => true,
                        'not null' => true
                    )
                ),

                'indexes' => array(
                    'shipping_state_cid' => array('shipping_state_cid'),
                    'shipping_size_cid' => array('shipping_size_cid')
                )
            ),

            'store_shipping_countries' => array(
                'fields' => array(
                    'cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'name' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    ),
                    'tax' => array(
                        'type' => 'double',
                        'not null' => true
                    )
                ),

                'indexes' => array(
                    'name' => array('name'),
                    'tax' => array('tax')
                ),

                'primary key' => array('cid')
            ),

            'store_shipping_states' => array(
                'fields' => array(
                    'cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'shipping_country_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'name' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    ),
                    'tax' => array(
                        'type' => 'double',
                        'not null' => true
                    )
                ),

                'indexes' => array(
                    'shipping_country_cid' => array('shipping_country_cid'),
                    'name' => array('name'),
                    'tax' => array('tax')
                ),

                'primary key' => array('cid')
            ),

            'store_settings' => array(
                'fields' => array(
                    'setting' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    ),
                    'value' => array(
                        'type' => 'text',
                        'size' => 'normal',
                        'not null' => true
                    )
                ),

                'indexes' => array(
                    'setting' => array('setting')
                )
            ),

            'store_related_products' => array(
                'fields' => array(
                    'product_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'related_product_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    )
                ),

                'indexes' => array(
                    'product_cid' => array('product_cid'),
                    'related_product_cid' => array('related_product_cid')
                )
            )
        );
    }

}
