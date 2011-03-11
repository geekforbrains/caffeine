<?php
/**
 * -----------------------------------------------------------------------------
 * PayPal_Pro
 * @author Gavin Vickery <gdvickery@gmail.com>
 *
 * Used for handling payments via the PayPal Payment Pro API.
 * -----------------------------------------------------------------------------
 */
class PayPal_Pro {
	
	private static $_params = array(
		'first_name' => 'FIRSTNAME',
		'last_name' => 'LASTNAME',
		'address' => 'STREET',
		'city' => 'CITY',
		'state' => 'STATE',
		'zip' => 'ZIP',
		'country_code' => 'COUNTRYCODE',
		'currency_code' => 'CURRENCYCODE',
		'payment_type' => 'PAYMENTACTION',
		'amount' => 'AMT',
		'cc_type' => 'CREDITCARDTYPE',
		'cc_num' => 'ACCT',
		'cc_exp' => 'EXPDATE',
		'cc_cvv2' => 'CVV2'
	);

	private static $_error = array(
		'code' => null,
		'message' => null
	);

	// Get errors, if any
	public static function error() {
		return self::$_error();
	}

	/**
	 * -------------------------------------------------------------------------
	 * Does a direct credit card payment.
	 * -------------------------------------------------------------------------
	 */
	public static function direct_payment($data)
	{
		$request = '';
		foreach(self::$_params as $k => $v)
			$request .= sprintf('&%s=%s', $v, urlencode($data[$k]));

		return self::_send('doDirectPayment', $request);
	}

	/**
	 * -------------------------------------------------------------------------
	 * Handles making payment requests via cURL.
	 * -------------------------------------------------------------------------
	 */
	private static function _send($method, $request)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, PAYPAL_PRO_API_URL);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);

		if(PAYPAL_PRO_API_MODE == '3TOKEN')
		{
			$request = sprintf(
				'METHOD=%s&VERSION=%s&PWD=%s&USER=%s&=SIGNATURE=%s%s',
				PAYPAL_PRO_API_MODE,
				PAYPAL_PRO_API_VERSION,
				PAYPAL_PRO_API_PASS,
				PAYPAL_PRO_API_USER,
				PAYPAL_PRO_API_SIGN,
				$request
			);
		}
		elseif(API_AUTHENTICATION_MODE == 'UNIPAY')
		{
			$request = sprintf(
				'METHOD=%s&VERSION=%s&SUBJECT=%s%s',
				PAYPAL_PRO_API_MODE,
				PAYPAL_PRO_API_VERSION,
				PAYPAL_PRO_API_SUBJECT,
				$request
			);
		}
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		$response = curl_exec($ch);

		if(curl_errno($ch))
		{
			self::$_error['code'] = curl_errno($ch);
			self::$_error['message'] = curl_error($ch);
		}

		curl_close($ch);

		if(!is_null(self::$_error['code']))
			return false;
		return $response;
	}

}
