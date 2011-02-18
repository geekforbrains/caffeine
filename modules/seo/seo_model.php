<?php
class SEO_Model {

	public static function get($path)
	{
		Database::query('SELECT * FROM {seo} WHERE path = %s', $path);

		if(Database::num_rows() > 0)
			return Database::fetch_array();
		return false;
	}

	public static function get_by_cid($cid)
	{
		Database::query('SELECT * FROM {seo} WHERE cid = %s', $cid);

		if(Database::num_rows() > 0)
			return Database::fetch_array();
		return false;
	}

	public static function get_all()
	{
		Database::query('SELECT * FROM {seo} ORDER BY cid DESC');
		return Database::fetch_all();
	}

	public static function exists($path)
	{
		Database::query('SELECT cid FROM {seo} WHERE path = %s', $path);

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
		Content::delete($cid);
		Database::query('DELETE FROM {seo} WHERE cid = %s', $cid);

		if(Database::affected_rows() > 0)
			return true;
		return false;
	}

}
