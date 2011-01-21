<?php
/**
 * =============================================================================
 * PayPal_Events
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
final class PayPal_Events {

	/**
	 * -------------------------------------------------------------------------
	 * Implements the Path::callbacks event.
	 * -------------------------------------------------------------------------
	 */
	public static function path_callbacks()
	{
		return array(
			'paypal/ipn' => array(
				'callback' => array('PayPal', 'ipn'),
				'auth' => true,
				'visible' => false
			)
		);
	}

}
