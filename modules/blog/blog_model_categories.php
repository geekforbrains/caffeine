<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Blog_Model_Categories
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class Blog_Model_Categories {

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function get_all()
    {
        Database::query('
			SELECT *
			FROM {blog_categories}
			ORDER BY name ASC
		');

        return Database::fetch_all();
    }

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function get_all_by_post_cid($cid)
	{
		Database::query('	
			SELECT DISTINCT
				bc.*
			FROM {blog_categories} bc
				JOIN {blog_post_categories} bpc ON bpc.category_cid = bc.cid
			WHERE
				bpc.post_cid = %s
			',
			$cid
		);

		$categories = array();
		$rows = Database::fetch_all();
		
		foreach($rows as $row)
			$categories[$row['cid']] = $row;

		return $categories;
	}
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function get_by_cid($cid)
    {
		Database::query('SELECT * FROM {blog_categories} WHERE cid = %s', $cid);
        return Database::fetch_array();
    }

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function get_by_slug($slug)
    {
        Database::query('SELECT * FROM {blog_categories} WHERE slug LIKE %s',
            $slug);

        return Database::fetch_array();
    }
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function exists($name)
    {   
        Database::query('SELECT cid FROM {blog_categories} WHERE name LIKE %s', 
			$name);

        if(Database::num_rows() > 0)
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

		return Database::insert('blog_categories', array(
			'cid' => $cid,
			'name' => $name,
			'slug' => $slug
		));
    }
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function delete($cid)
    {
		// Get blog posts associated with this category
		// If those blog posts dont have any other categories associated with
		// them, mark them as drafts
		$posts = Blog_Model_Posts::get_all_by_category_cid($cid);
				
		foreach($posts as $post)
		{
			Database::query('
				SELECT * FROM {blog_post_categories} WHERE post_cid = %s',
				$post['cid']
			);

			if(Database::num_rows() <= 1)
			{
				Database::update('blog_posts',
					array('published' => 0),
					array('cid' => $post['cid'])
				);
			}
		}

		Content::delete($cid);
		Database::delete('blog_post_categories', array('category_cid' => $cid));
		return Database::delete('blog_categories', array('cid' => $cid));
    }
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function update($cid, $name, $slug)
    {
		Content::update($cid);

		return Database::update('blog_categories',
			array(
				'name' => $name,
				'slug' => $slug
			),
			array('cid' => $cid)
		);
    }

}
