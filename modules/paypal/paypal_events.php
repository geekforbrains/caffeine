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
			'paypal/ipn/%s' => array(
				'callback' => array('PayPal', 'ipn'),
				'auth' => true,
				'visible' => false
			)
		);
	}

	/**
	 * -------------------------------------------------------------------------
	 * Implements the Database::install event.
	 * -------------------------------------------------------------------------
	 */
	public static function database_install()
	{
		return array(
			'paypal_transactions' => array(
				'fields' => array(
					'cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'data' => array(
						'type' => 'text',
						'size' => 'normal',
						'not null' => true
					)
				),

				'primary key' => array('cid')
			)
		);
	}

}
