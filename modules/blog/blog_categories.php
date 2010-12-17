<?php
class Blog_Categories extends Database {

    public static function get_all()
    {
        self::query('SELECT * FROM {blog_categories} ORDER BY name ASC');
        return self::fetch_all();
    }

	public static function get_all_by_post_cid($cid)
	{
		self::query('
			SELECT DISTINCT
				bc.*
			FROM {blog_categories} bc
				LEFT JOIN {content_relatives} cr ON cr.parent_cid = %s
			ORDER BY
				bc.name
			ASC
		', $cid);

		return self::fetch_all();
	}
    
    public static function get_by_cid($cid)
    {
		self::query('SELECT * FROM {blog_categories} WHERE cid = %s', $cid);
        return self::fetch_array();
    }

    public static function get_by_slug($slug)
    {
        self::query('SELECT * FROM {blog_categories} WHERE slug LIKE %s',
            $slug);
        return self::fetch_array();
    }
    
    public static function exists($name)
    {   
        self::query('SELECT cid FROM {blog_categories} WHERE name LIKE %s', $name);
        if(self::num_rows() > 0)
            return true;
        return false;
    }
    
    public static function create($name, $slug)
    {
		$cid = Content::create(BLOG_TYPE_CATEGORY);

        self::query('
			INSERT INTO {blog_categories} (cid, name, slug) VALUES 
			(%s, %s, %s)', $cid, $name, $slug);
    }
    
    public static function delete($cid)
    {
        self::query('DELETE FROM {blog_categories} WHERE cid = %s', $cid);
		Content::delete($cid);
    }
    
    public static function update($cid, $name, $slug)
    {
        self::query('
            UPDATE {blog_categories} SET
                name = %s,
                slug = %s
            WHERE
                cid = %s
            ',
            $name, $slug, $cid
        );

		Content::update($cid);
    }

}
