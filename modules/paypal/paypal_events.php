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
					'transaction_id' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					),
					'transaction_type' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					),
					'payment_type' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					),
					'payment_status' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					),
					'data' => array(
						'type' => 'text',
						'size' => 'big',
						'not null' => true
					),
					'timestamp' => array(
						'type' => 'int',
						'size' => 'normal',
						'unsigned' => true,
						'not null' => true
					)
				)
			)
		);
	}

}
