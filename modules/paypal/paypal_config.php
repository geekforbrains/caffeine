<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * PayPal Configurations
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
define('PAYPAL_PRO_API_VERSION', '');
