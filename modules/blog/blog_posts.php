<?php
class Blog_Posts extends Database {

    public static function get_all()
    {
		self::query('
			SELECT
				bp.*
			FROM {blog_posts} bp
				LEFT JOIN {content} c ON c.id = bp.cid
			ORDER BY
				c.created
			DESC
		');

		return self::_get_categories(self::fetch_all());
    }

    public static function get_all_by_category_slug($slug)
    {
		self::query('
			SELECT
				bp.*
			FROM {blog_posts} bp
				LEFT JOIN {content_relatives} cr ON cr.parent_cid = bp.cid
				LEFT JOIN {blog_categories} bc ON bc.cid = cr.child_cid
			WHERE
				bc.slug = %s
			', 
			$slug
		);

		return self::_get_categories(self::fetch_all());
    }

    public static function get_by_slug($slug)
    {
        self::query('SELECT * FROM {blog_posts} WHERE slug = %s', $slug);
        return self::_get_categories(self::fetch_array());
    }
    
    public static function get_by_cid($cid)
    {
        self::query('SELECT * FROM {blog_posts} WHERE cid = %s', $cid);
        return self::_get_categories(self::fetch_array());
    }
    
    public static function create($title, $content, $slug, $categories = array())
    {
		$cid = Content::create(BLOG_TYPE_POST, $categories);

        self::query('
            INSERT INTO {blog_posts} (
				cid,
                title, 
                content, 
                slug
            ) VALUES (
				%s, %s, %s, %s
			)', 
			$cid,
			$title,
			$content,
			$slug
        );

		if(self::affected_rows() > 0)
			return true;

		return false;
    }
    
    public static function update($cid, $title, $content, $slug)
    {
        self::query('
            UPDATE {blog_posts} SET
                title = %s,
                content = %s,
                slug = %s
            WHERE
                cid = %s
            ', 
            $title, $content, $slug, $cid
        );

		if(self::affected_rows() > 0)
		{
			Content::update($cid);
			return true;
		}

		return false;
    }
    
    public static function delete($cid)
    {
        self::query('DELETE FROM {blog_posts} WHERE cid = %s', $cid);

		if(self::affected_rows() > 0)
		{
			Content::delete($cid);
			return true;
		}

		return false;
    }

	private static function _get_categories($rows)
	{
		// Check if we are getting for multiple rows
		if(isset($rows[0]))
		{
			foreach($rows as &$row)
				$row['categories'] = Blog_Categories::get_all_by_post_cid(
					$row['cid']);
		}
		elseif($rows)
			$rows['categories'] = Blog_Categories::get_all_by_post_cid($rows['cid']);

		return $rows;
	}

}
