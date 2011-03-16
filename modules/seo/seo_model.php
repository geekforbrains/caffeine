<?php
class SEO_Model {

	public static function get_all()
	{
		Database::query('
			SELECT 
				sp.*,
				c.created,
				c.updated
			FROM {seo_paths} sp
				JOIN {content} c ON c.id = sp.cid
			WHERE
				c.site_cid = %s
			ORDER BY
				sp.is_default DESC,
				sp.path ASC
			',
			User::current_site()
		);

		return Database::fetch_all();
	}

	public static function get_by_path($path)
	{
		Database::query('
			SELECT 
				sp.*,
				c.created,
				c.updated
			FROM {seo_paths} sp
				JOIN {content} c ON c.id = sp.cid
			WHERE sp.path_hash = MD5(%s)
				AND c.site_cid = %s
			', 
			$path,
			User::current_site()
		);

		if(Database::num_rows() > 0)
		{
			$row = Database::fetch_array();
			$row['meta'] = self::get_all_meta_by_cid($row['cid']);
			return $row;
		}

		return false;
	}

	public static function get_default_path()
	{
		Database::query('
			SELECT 
				sp.*,
				c.created,
				c.updated
			FROM {seo_paths} sp
				JOIN {content} c ON c.id = sp.cid
			WHERE sp.is_default = 1
				AND c.site_cid = %s
			LIMIT 1
			', 
			User::current_site()
		);

		if(Database::num_rows() > 0)
		{
			$row = Database::fetch_array();
			$row['meta'] = self::get_all_meta_by_cid($row['cid']);
			return $row;
		}

		return false;
	}

	public static function get_by_cid($cid)
	{
		Database::query('
			SELECT 
				sp.*,
				c.created,
				c.updated
			FROM {seo_paths} sp
				JOIN {content} c ON c.id = sp.cid
			WHERE sp.cid = %s
				AND c.site_cid = %s
			', 
			$cid,
			User::current_site()
		);

		if(Database::num_rows() > 0)
		{
			$row =  Database::fetch_array();
			$row['meta'] = self::get_all_meta_by_cid($row['cid']);
			return $row;
		}

		return false;
	}

	public static function get_all_meta_by_cid($cid)
	{
		Database::query('SELECT * FROM {seo_meta} WHERE seo_path_cid = %s', $cid);
		return Database::fetch_all();
	}

	public static function exists($path)
	{
		Database::query('
			SELECT 
				sp.cid 
			FROM {seo_paths} sp
				JOIN {content} c ON c.id = sp.cid
			WHERE sp.path_hash = MD5(%s)
				AND c.site_cid = %s
			', 
			$path,
			User::current_site()
		);

		if(Database::num_rows() > 0)
			return true;
		return false;
	}

	public static function create($path, $title, $is_default)
	{
		// If is default, remove any old defaults (there can only be one)
		if($is_default == 1)
			Database::update('seo_paths', array('is_default' => 0));

		$cid = Content::create(SEO_TYPE_PATH);
		$status = Database::insert('seo_paths', array(
			'cid' => $cid,
			'path' => $path,
			'path_hash' => md5($path),
			'title' => $title,
			'is_default' => $is_default
		));

		if($status)
			return $cid;
		return false;
	}

	public static function update($cid, $path, $title, $prepend, $append, $is_default)
	{
		if($is_default == 1)
			Database::update('seo_paths', array('is_default' => 0));

		Content::update($cid);
		Database::update('seo_paths',
			array(
				'path' => $path,
				'title' => $title,
				'prepend' => $prepend,
				'append' => $append,
				'is_default' => $is_default
			),
			array('cid' => $cid)
		);

		return true;
	}

	public static function create_meta($path_cid, $name, $content, $is_httpequiv)
	{
		$cid = Content::create(SEO_TYPE_META);
		$status = Database::insert('seo_meta', array(
			'cid' => $cid,
			'seo_path_cid' => $path_cid,
			'name' => $name,
			'content' => $content,
			'is_httpequiv' => $is_httpequiv
		));

		if($status)
			return $cid;
		return false;
	}

	public static function delete($cid)
	{
		// Make sure we can only delete stuff on our site
		if(self::get_by_cid($cid))
		{
			// Delete meta for this path
			$meta = self::get_all_meta_by_cid($cid);
			foreach($meta as $m)
				self::delete_meta($m['cid']);

			Content::delete($cid);
			Database::delete('seo_paths', array('cid' => $cid));
			return true;
		}

		return false;
	}

	public static function delete_meta($cid)
	{
		Content::delete($cid);
		Database::delete('seo_meta', array('cid' => $cid));
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
