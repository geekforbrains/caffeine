<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * PayPal Configurations
 *
 * Sandbox URL: https://api-3t.sandbox.paypal.com/nvp
 * Live URL: https://api-3t.paypal.com/nvp
 * =============================================================================
 */
// PayPal Standard
define('PAYPAL_URL', 'ssl://www.sandbox.paypal.com');

// PayPal Pro
define('PAYPAL_PRO_API_URL', 'https://api-3t.sandbox.paypal.com/nvp');
define('PAYPAL_PRO_API_USER', '');
define('PAYPAL_PRO_API_PASS', '');
define('PAYPAL_PRO_API_SIGN', '');
define('PAYPAL_PRO_API_SUBJECT', ''); // UNIPAY only
define('PAYPAL_PRO_API_MODE', '3TOKEN'); // 3TOKEN (default) or UNIPAY
define('PAYPAL_PRO_API_VERSION', '60.0');

// PayPal Express
define('PAYPAL_EXPRESS_API_URL', 'https://api-3t.sandbox.paypal.com/nvp');
define('PAYPAL_EXPRESS_API_CHECKOUT_URL', 'https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token='); // Must add token to end in code
define('PAYPAL_EXPRESS_API_USER', '');
define('PAYPAL_EXPRESS_API_PASS', '');
define('PAYPAL_EXPRESS_API_SIGN', '');
define('PAYPAL_EXPRESS_API_VERSION', '64.0');

/**
 * =============================================================================
 * PayPal Constants
 * =============================================================================
 */
define('PAYPAL_TYPE_TRANSACTION', 'paypal_transaction');
