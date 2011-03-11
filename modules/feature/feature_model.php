<?php
class Feature_Model {

	public static function get_by_cid($cid)
	{
		Database::query('
			SELECT
				f.*,
				c.created,
				c.updated
			FROM {features} f
				JOIN {content} c ON c.id = f.cid
			WHERE f.cid = %s
				AND c.site_cid = %s
			',
			$cid,
			User::current_site()
		);

		if(Database::num_rows() > 0)
			return Database::fetch_array();
		return false;
	}

	public static function get_by_area_cid($area_cid)
	{
		Database::query('
			SELECT
				f.*,
				c.created,
				c.updated
			FROM {features} f
				JOIN {content} c ON c.id = f.cid
			WHERE f.area_cid = %s
				AND c.site_cid = %s
			LIMIT 1
			',
			$area_cid,
			User::current_site()
		);

		if(Database::num_rows() > 0)
			return Database::fetch_array();
		return false;
	}

	public static function get_all_by_area_cid($area_cid)
	{
		Database::query('
			SELECT
				f.*,
				c.created,
				c.updated
			FROM {features} f
				JOIN {content} c ON c.id = f.cid
			WHERE f.area_cid = %s
				AND c.site_cid = %s
			',
			$area_cid,
			User::current_site()
		);

		return Database::fetch_all();
	}

}
