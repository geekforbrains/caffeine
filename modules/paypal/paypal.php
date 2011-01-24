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

}
