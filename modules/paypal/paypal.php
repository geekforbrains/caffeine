<?php
/**
 * =============================================================================
 * PayPal
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 *
 * @event ipn
 *		Triggers an event when an IPN request is made from PayPal
 * =============================================================================
 */
class PayPal {

	// Store curl error info
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
	 * Handles IPN responses from PayPal.
	 * -------------------------------------------------------------------------
	 */
	public static function ipn($key)
	{
		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';

		foreach($_POST as $key => $value)
		{
			$value = urlencode(stripslashes($value));
			$req .= "&$key=$value";
		}

		// post back to PayPal system to validate
		$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

		$fp = fsockopen(PAYPAL_URL, 443, $errno, $errstr, 30);

		if(!$fp) 
		{
			echo 'HTTP ERROR';
			// HTTP ERROR
		} 
		else 
		{
			fputs($fp, $header . $req);

			while(!feof($fp)) 
			{
				$res = fgets($fp, 1024);
				if(strcmp($res, "VERIFIED") == 0)
				{
					Database::insert('paypal_transactions', array(
						'transaction_id' => $_POST['txn_id'],
						'transaction_type' => $_POST['txn_type'],
						'payment_type' => $_POST['payment_type'],
						'payment_status' => $_POST['payment_status'],
						'data' => serialize($_POST),
						'timestamp' => time()
					));

					Caffeine::trigger('Paypal', 'ipn', array($key => $_POST));
				}
				elseif(strcmp($res, "INVALID") == 0)
				{
					// INVALID TRANSACTION
				}
			}

			fclose ($fp);
		}

		exit;
	}

	/**
	 * -------------------------------------------------------------------------
	 * For payments via Payments Pro. Handles making payment requests via cURL.
	 *
	 * @param $method
	 *		The API method to perform. 
	 *
	 *		Examples:
	 *			DoDirectPayment
	 *			DoExpressCheckoutPayment
	 *			CreateRecurringPaymentsProfile
	 *
	 * @param $data
	 *		A key, value array of API fields and their associated data. The
	 *		key should be the API field specified by PayPal and the value the
	 *		value associated with that field.
	 *
	 *		Values are url encoded automatically.
	 *
	 *		Example:
	 *			array(
	 *				'CREDITCARDTYPE' => 'Visa',
	 *				'FIRSTNAME' => 'John',
	 *				'LASTNAME' => 'Doe'
	 *				etc..
	 *			);
	 *
	 * @return 
	 *		A transaction CID associated to the data stored in the database.
	 * -------------------------------------------------------------------------
	 */
	public static function process($method, $data)
	{
		// Encode data and build request string
		$request = '';
		foreach($data as $k => $v)
			$request .= sprintf('&%s=%s', $k, urlencode($v));	

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
				'METHOD=%s&VERSION=%s&PWD=%s&USER=%s&SIGNATURE=%s%s',
				urlencode($method),
				urlencode(PAYPAL_PRO_API_VERSION),
				urlencode(PAYPAL_PRO_API_PASS),
				urlencode(PAYPAL_PRO_API_USER),
				urlencode(PAYPAL_PRO_API_SIGN),
				$request
			);
		}
		elseif(PAYPAL_PRO_API_MODE == 'UNIPAY')
		{
			$request = sprintf(
				'METHOD=%s&VERSION=%s&SUBJECT=%s%s',
				$method,
				PAYPAL_PRO_API_VERSION,
				PAYPAL_PRO_API_SUBJECT,
				$request
			);
		}
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		$response = curl_exec($ch);

		// Store errors if cURL fails
		if(curl_errno($ch))
		{
			self::$_error['code'] = curl_errno($ch);
			self::$_error['message'] = curl_error($ch);
		}

		curl_close($ch);

		if(!is_null(self::$_error['code']))
			return false;

		$data = self::_parse_response($response);

		if($data['ACK'] == 'Success')
		{
			$transaction_cid = PayPal_Model::create_transaction($data);

			return array(
				'cid' => $transaction_cid,
				'data' => $data,
				'status' => true
			);
		}

		return array(
			'data' => $data,
			'status' => false
		);
	}

    /**
     * SetExpressCheckout
     *
     * @param double $amount
     * @param string $currenty Can be USD, GBP, EUR, JPY, CAD, AUD
     * @param string $return_url
     * @param string $cancel_url
     * @param string $payment_type Can be Authorization, Sale or Order
     */
    public static function expressRedirect($amount, $currency, $return_url, $cancel_url, $extra_data = '', $payment_type = 'Sale')
    {
        $request = sprintf(
            '&PAYMENTREQUEST_0_AMT=%s&RETURNURL=%s&CANCELURL=%s&PAYMENTREQUEST_0_PAYMENTACTION=%s&PAYMENTREQUEST_0_CURRENCYCODE=%s',
            urlencode($amount),
            urlencode($return_url),
            urlencode($cancel_url),
            urlencode($payment_type),
            urlencode($currency)
        );

        $request .= $extra_data;

        $response = self::_post_request('SetExpressCheckout', $request);

        if(strtoupper($response['ACK']) ==='SUCCESS' || strtoupper($response['ACK']) == 'SUCCESSWITHWARNING')
        {
            $token = urldecode($response['TOKEN']);
            $checkout_url = PAYPAL_EXPRESS_API_CHECKOUT_URL . $token;
            Router::redirect($checkout_url);
            die();
        }

        return false;
    }

    /**
     * GetExpressCheckoutDetails
     */
    public static function expressDetails()
    {
        if(!isset($_REQUEST['token']))
            die('Token not set.');

        $token = urlencode(htmlspecialchars($_REQUEST['token']));
        $request = '&TOKEN=' . $token;
        $response = self::_post_request('GetExpressCheckoutDetails', $request);

        if(strtoupper($response['ACK']) == 'SUCCESS' || strtoupper($response['ACK']) == 'SUCCESSWITHWARNING')
            return $response;

        return false;
    }

    /**
     * DoExpressCheckoutPayment
     */
    public static function expressProcess($payer_id, $amount, $currency, $payment_type = 'Sale')
    {
        $token = urlencode($_REQUEST['token']);

        $request = sprintf(
            '&TOKEN=%s&PAYERID=%s&PAYMENTACTION=%s&AMT=%s&CURRENCYCODE=%s',
            $token,
            $payer_id,
            $payment_type,
            $amount,
            $currency
        );

        $response = self::_post_request('DoExpressCheckoutPayment', $request);

        if(strtoupper($response['ACK']) == 'SUCCESS' || strtoupper($response['ACK']) == 'SUCCESSWITHWARNING')
            return $response;

        return false;
    }

    /**
     * TODO
     */
    private static function _post_request($method, $request)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, PAYPAL_EXPRESS_API_URL);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $nvpreq = sprintf(
            'METHOD=%s&VERSION=%s&PWD=%s&USER=%s&SIGNATURE=%s%s',
            urlencode($method),
            urlencode(PAYPAL_EXPRESS_API_VERSION),
            urlencode(PAYPAL_EXPRESS_API_PASS),
            urlencode(PAYPAL_EXPRESS_API_USER),
            urlencode(PAYPAL_EXPRESS_API_SIGN),
            $request
        );
            
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
        $httpResponse = curl_exec($ch);

        if(!$httpResponse)
            die(curl_error($ch) . '(' . curl_errno($ch) . ')');

        $httpResponseAr = explode('&', $httpResponse);

        $httpParsedResponseAr = array();
        foreach ($httpResponseAr as $i => $value) {
            $tmpAr = explode("=", $value);
            if(sizeof($tmpAr) > 1) {
                $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
            }
        }

        if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
            die(print_r($httpParsedResponseAr, true));
        }

        return $httpParsedResponseAr;
    }
	
	/**
	 * Parses a PayPal payment response into an array of key, value pairs.
	 */
	private static function _parse_response($response)
	{
		$bits = explode('&', $response);
		$data = array();
		foreach($bits as $bit)
		{
			$b = explode('=', $bit);
			$data[$b[0]] = urldecode($b[1]);
		}
		return $data;
	}

}
