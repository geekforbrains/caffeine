<?php
class SEO_Model {

	public static function get($path)
	{
		Database::query('
			SELECT 
				s.*,
				c.created,
				c.updated
			FROM {seo} s
				JOIN {content} c ON c.id = s.cid
			WHERE path = %s
				AND c.site_cid = %s
			', 
			$path,
			User::current_site()
		);

		if(Database::num_rows() > 0)
			return Database::fetch_array();
		return false;
	}

	public static function get_by_cid($cid)
	{
		Database::query('
			SELECT 
				s.*,
				c.created,
				c.updated
			FROM {seo} s
				JOIN {content} c ON c.id = s.cid
			WHERE cid = %s
				AND c.site_cid = %s
			', 
			$cid,
			User::current_site()
		);

		if(Database::num_rows() > 0)
			return Database::fetch_array();
		return false;
	}

	public static function get_all()
	{
		Database::query('
			SELECT 
				s.*,
				c.created,
				c.updated
			FROM {seo} s
				JOIN {content} c ON c.id = s.cid
			WHERE
				c.site_cid = %s
			ORDER BY cid DESC
			',
			User::current_site()
		);

		return Database::fetch_all();
	}

	public static function exists($path)
	{
		Database::query('
			SELECT 
				s.cid 
			FROM {seo} s 
				JOIN {content} c ON c.id = s.cid
			WHERE path = %s
				AND c.site_cid = %s
			', 
			$path,
			User::current_site()
		);

		if(Database::num_rows() > 0)
			return true;
		return false;
	}

	public static function create($path, $title, $author, $description, $keywords, $robots)
	{
		$cid = Content::create(SEO_TYPE);

		Database::insert('seo', array(
			'cid' => $cid,
			'path' => $path,
			'title' => $title,
			'meta_author' => $author,
			'meta_description' => $description,
			'meta_keywords' => $keywords,
			'meta_robots' => $robots
		));

		return $cid;
	}

	public static function update($cid, $path, $title, $author, $description, $keywords, $robots)
	{
		Content::update($cid);

		Database::update('seo',
			array(
				'path' => $path,
				'title' => $title,
				'meta_author' => $author,
				'meta_description' => $description,
				'meta_keywords' => $keywords,
				'meta_robots' => $robots
			),
			array('cid' => $cid)
		);
	}

	public static function delete($cid)
	{
		// Make sure we can only delete stuff on our site
		if(self::get_by_cid($cid))
		{
			Content::delete($cid);
			Database::delete('seo', array('cid' => $cid));
			return true;
		}

		return false;
	}

	public static function get_analytics()
	{
		Database::query('
			SELECT
				sa.*,
				c.created,
				c.updated
			FROM {seo_analytics} sa
				JOIN {content} c ON c.id = sa.cid
			WHERE
				c.site_cid = %s
			LIMIT 1
			',
			User::current_site()
		);

		if(Database::num_rows() > 0)
			return Database::fetch_array();

		return array('cid' => 0, 'code' => null);
	}

	public static function update_analytics($code)
	{
		$tmp = self::get_analytics();
		$cid = $tmp['cid'];

		// No code created yet
		if($cid == 0)
		{
			$cid = Content::create(SEO_TYPE_ANALYTICS);
			Database::insert('seo_analytics', array(
				'cid' => $cid,
				'code' => $code
			));
		}
		else
		{
			Content::update($cid);
			Database::update('seo_analytics',
				array('code' => $code),
				array('cid' => $cid)
			);
		}
	}

}
