<?php
class PayPal_Model {

	public static function create_transaction($data)
	{
		$cid = Content::create(PAYPAL_TYPE_TRANSACTION);
		$status = Database::insert('paypal_transactions', array(
			'cid' => $cid,
			'data' => serialize($data)
		));

		if($status)
			return $cid;	
		return false;
	}

	public static function get_transaction($cid)
	{
		Database::query('
			SELECT
				pt.*,
				c.created,
				c.updated
			FROM {paypal_transactions} pt
				JOIN {content} c ON c.id = pt.cid
			WHERE pt.cid = %s
				AND c.site_cid = %s
			',
			$cid,
			User::current_site()
		);

		if(Database::num_rows() > 0)
			return Database::fetch_array();
		return false;
	}

}
