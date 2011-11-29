<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Store Configurations
 * =============================================================================
 */
define('STORE_EMAIL_FROM', 'orders@example.com'); // The email used when sending emails to customers
define('STORE_EMAIL_FROM_NAME', 'My Store'); // The name used when sending emails to customers
define('STORE_EMAIL_ADMIN', ''); // The email sent to when notifying admin of orders

/**
 * =============================================================================
 * Store Constants
 * =============================================================================
 */
define('STORE_TYPE_COUNTRY', 'store_country');
define('STORE_TYPE_STATE', 'store_state');
define('STORE_TYPE_SIZE', 'store_size');
define('STORE_TYPE_CATEGORY', 'store_category');
define('STORE_TYPE_PRODUCT', 'store_product');
define('STORE_TYPE_OPTION_TYPE', 'store_option_type');
define('STORE_TYPE_OPTION', 'store_option');
define('STORE_TYPE_ORDER', 'store_order');
define('STORE_TYPE_ORDER_PRODUCT', 'store_order_product');
define('STORE_TYPE_CUSTOMER', 'store_customer');
