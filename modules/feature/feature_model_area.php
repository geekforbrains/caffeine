<?php
class Feature_Model_Area {

	public static function get_all()
	{
		Database::query('
			SELECT 
				fa.*,
				c.created,
				c.updated
			FROM {feature_areas} fa
				JOIN {content} c ON c.id = fa.cid
			WHERE
				c.site_cid = %s
			ORDER BY
				fa.name
			',
			User::current_site()
		);

		return Database::fetch_all();
	}

	public static function get_by_cid($cid)
	{
		Database::query('
			SELECT
				fa.*,
				c.created,
				c.updated
			FROM {feature_areas} fa
				JOIN {content} c ON c.id = fa.cid
			WHERE fa.cid = %s
				AND c.site_cid = %s
			',
			$cid,
			User::current_site()
		);

		if(Database::num_rows() > 0)
			return Database::fetch_array();
		return false;
	}

	public static function exists($tag)
	{
		Database::query('
			SELECT 
				fa.cid 
			FROM {feature_areas} fa 
				JOIN {content} c ON c.id = fa.cid
			WHERE fa.tag LIKE %s
				AND c.site_cid = %s
			',
			$tag,
			User::current_site()
		);

		if(Database::num_rows() > 0)
			return Database::fetch_single('cid'); 
		return false;
	}

	public static function create($post)
	{
		$cid = Content::create(FEATURE_TYPE_AREA);
		$status =Database::insert('feature_areas', array(
			'cid' => $cid,
			'name' => $post['name'],
			'tag' => $post['tag'],
			'has_title' => $post['has_title'],
			'has_body' => $post['has_body'],
			'has_link' => $post['has_link'],
			'has_image' => $post['has_image'],
			'image_width' => $post['image_width'],
			'image_height' => $post['image_height'],
			'multiple_features' => $post['multiple_features'],
			'multiple_images' => $post['multiple_images']
		));

		if($status)
			return $cid;
		return false;
	}

	public static function update($cid, $post)
	{
		Content::update($cid);
		Database::update('feature_areas', 
			array(
				'name' => $post['name'],
				'tag' => $post['tag'],
				'has_title' => $post['has_title'],
				'has_body' => $post['has_body'],
				'has_link' => $post['has_link'],
				'has_image' => $post['has_image'],
				'image_width' => $post['image_width'],
				'image_height' => $post['image_height'],
				'multiple_features' => $post['multiple_features'],
				'multiple_images' => $post['multiple_images']
			),
			array('cid' => $cid)
		);

		return true;
	}

	public static function delete($cid)
	{
		if(self::get_by_cid($cid))
		{
			Content::delete($cid);
			return Database::delete('feature_areas', array('cid' => $cid));
		}
		return false;
	}

}
