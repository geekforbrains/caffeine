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
		{
			$row = Database::fetch_array();
			$row['images'] = self::get_images_by_cid($row['cid']);
			return $row;
		}

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
		{
			$row = Database::fetch_array();
			$row['images'] = self::get_images_by_cid($row['cid']);
			return $row;
		}

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

		$rows = Database::fetch_all();
		foreach($rows as &$row)
			$row['images'] = self::get_images_by_cid($row['cid']);

		return $rows;
	}

	public static function get_by_tag($tag)
	{
		Database::query('
			SELECT
				f.*
			FROM {features} f
				JOIN {feature_areas} fa ON fa.cid = f.area_cid
				JOIN {content} c ON c.id = f.cid
			WHERE fa.tag = %s
				AND c.site_cid = %s
			LIMIT 1
			',
			$tag,
			User::current_site()
		);

		if(Database::num_rows() > 0)
		{
			$row = Database::fetch_array();
			$row['images'] = self::get_images_by_cid($row['cid']);
			return $row;
		}

		return false;
	}

	public static function get_all_by_tag($tag)
	{
		Database::query('
			SELECT
				f.*
			FROM {features} f
				JOIN {feature_areas} fa ON fa.cid = f.area_cid
				JOIN {content} c ON c.id = f.cid
			WHERE fa.tag = %s
				AND c.site_cid = %s
			',
			$tag,
			User::current_site()
		);

		$rows = Database::fetch_all();
		foreach($rows as &$row)
			$row['images'] = self::get_images_by_cid($row['cid']);

		return $rows;
	}

	public static function get_random_by_tag($tag)
	{
		Database::query('
			SELECT
				f.*
			FROM {features} f
				JOIN {feature_areas} fa ON fa.cid = f.area_cid
				JOIN {content} c ON c.id = f.cid
			WHERE fa.tag = %s
				AND c.site_cid = %s
			ORDER BY
				RAND()
			LIMIT 1
			',
			$tag,
			User::current_site()
		);

		if(Database::num_rows() > 0)
		{
			$row = Database::fetch_array();
			$row['images'] = self::get_images_by_cid($row['cid']);
			return $row;
		}

		return false;
	}

	public static function get_images_by_cid($cid)
	{
		Database::query('
			SELECT
				fi.media_cid,
				fa.image_width AS width,
				fa.image_height AS height
			FROM {feature_images} fi
				JOIN {features} f ON f.cid = fi.feature_cid
				JOIN {feature_areas} fa ON fa.cid = f.area_cid
			WHERE
				f.cid = %s
			ORDER BY
				fi.media_cid
			',
			$cid
		);

		return Database::fetch_all();
	}

	public static function create($area_cid, $data)
	{
		$cid = Content::create(FEATURE_TYPE);
		$status = Database::insert('features', array(
			'cid' => $cid,
			'area_cid' => $area_cid,
			'title' => isset($data['title']) ? $data['title'] : null,
			'body' => isset($data['body']) ? $data['body'] : null,
			'link' => isset($data['link']) ? $data['link'] : null
		));

		if($status)
			return $cid;
		return false;
	}

	public static function update($feature_cid, $data)
	{
		Content::update($feature_cid);
		Database::update('features',
			array(
				'title' => isset($data['title']) ? $data['title'] : null,
				'body' => isset($data['body']) ? $data['body'] : null,
				'link' => isset($data['link']) ? $data['link'] : null
			),
			array('cid' => $feature_cid)
		);

		return true;
	}

	public static function add_image($feature_cid, $media_cid)
	{
		return Database::insert('feature_images', array(
			'feature_cid' => $feature_cid,
			'media_cid' => $media_cid
		));
	}

	public static function update_image($feature_cid, $media_cid)
	{
		// First get current media cid, and delete that image
		Database::query('SELECT media_cid FROM {feature_images} WHERE feature_cid = %s', $feature_cid);
		Media::delete(Database::fetch_single('media_cid'));

		return Database::update('feature_images',
			array('media_cid' => $media_cid),
			array('feature_cid' => $feature_cid)
		);
	}

	public static function delete_image($feature_cid, $media_cid)
	{
		Media::delete($media_cid);
		Database::delete('feature_images', array(
			'feature_cid' => $feature_cid,
			'media_cid' => $media_cid
		));
	}

}
