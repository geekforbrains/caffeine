<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Blog_Categories
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class Blog_Categories extends Database {

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function get_all()
    {
        self::query('SELECT * FROM {blog_categories} ORDER BY name ASC');
        return self::fetch_all();
    }

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function get_all_by_post_cid($cid)
	{
		self::query('
			SELECT DISTINCT
				bc.*
			FROM {blog_categories} bc
				LEFT JOIN {content_relatives} cr ON cr.cid = %s
			ORDER BY
				bc.name
			ASC
		', $cid);

		return self::fetch_all();
	}
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function get_by_cid($cid)
    {
		self::query('SELECT * FROM {blog_categories} WHERE cid = %s', $cid);
        return self::fetch_array();
    }

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function get_by_slug($slug)
    {
        self::query('SELECT * FROM {blog_categories} WHERE slug LIKE %s',
            $slug);
        return self::fetch_array();
    }
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function exists($name)
    {   
        self::query('SELECT cid FROM {blog_categories} WHERE name LIKE %s', $name);
        if(self::num_rows() > 0)
            return true;
        return false;
    }
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function create($name, $slug)
    {
		$cid = Content::create(BLOG_TYPE_CATEGORY);

        self::query('
			INSERT INTO {blog_categories} (cid, name, slug) VALUES 
			(%s, %s, %s)', $cid, $name, $slug);
    }
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function delete($cid)
    {
        self::query('DELETE FROM {blog_categories} WHERE cid = %s', $cid);
		Content::delete($cid);
    }
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
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
