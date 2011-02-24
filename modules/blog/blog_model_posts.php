<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Blog_Model_Posts
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class Blog_Model_Posts {

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function get_all($published = null, $limit = BLOG_POSTS_LIMIT)
    {
		if(!is_null($published))
		{
			Database::query('
				SELECT 
					bp.*,
					c.created,
					c.updated
				FROM {blog_posts} bp
					JOIN {content} c ON c.id = bp.cid
				WHERE bp.published = %s
					AND c.site_cid = %s
				ORDER BY c.created DESC
				LIMIT ' .$limit,
				$published,
				User::current_site()
			);
		}
		else
		{
			Database::query('
				SELECT 
					bp.*,
					c.created,
					c.updated
				FROM {blog_posts} bp
					JOIN {content} c ON c.id = bp.cid
				WHERE
					c.site_cid = %s
				ORDER BY c.created DESC
				LIMIT ' .$limit,
				User::current_site()
			);
		}

		return self::_get_categories(Database::fetch_all());
    }

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function get_latest($published = 1)
	{
		Database::query('
			SELECT
				bp.*,
				c.created,
				c.updated
			FROM {blog_posts} bp
				JOIN {content} c ON c.id = bp.cid
			WHERE
				bp.published = %s
				AND c.site_cid = %s
			ORDER BY
				c.created DESC
			LIMIT 1
			',
			$published,
			User::current_site()
		);

		if(Database::num_rows() > 0)
			return Database::fetch_array();
		return false;
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function get_all_by_category_slug($slug)
    {
		Database::query('
			SELECT
				bp.*,
				c.created,
				c.updated
			FROM {blog_posts} bp
				JOIN {content} c ON c.id = bp.cid
				JOIN {blog_post_categories} bpc ON bpc.post_cid = bp.cid
				JOIN {blog_categories} bc ON bc.cid = bpc.category_cid
			WHERE
				bc.slug = %s
				AND c.site_cid = %s
			',
			$slug,
			User::current_site()
		);

		return Database::fetch_all();
    }

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function get_all_by_category_cid($cid)
    {
		Database::query('
			SELECT
				bp.*,
				c.created
			FROM {blog_posts} bp
				JOIN {content} c ON c.id = bp.cid
				JOIN {blog_post_categories} bpc ON bpc.post_cid = bp.cid
				JOIN {blog_categories} bc ON bc.cid = bpc.category_cid
			WHERE
				bc.cid = %s
				AND c.site_cid = %s
			',
			$cid,
			User::current_site()
		);

		return Database::fetch_all();
    }

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function get_by_slug($slug)
    {
        Database::query('
			SELECT 
				bp.*,
				c.created,
				c.updated
			FROM {blog_posts} bp
				JOIN {content} c ON c.id = bp.cid
			WHERE bp.slug = %s
				AND c.site_cid = %s
			', 
			$slug,
			User::current_site()
		);

        return self::_get_categories(Database::fetch_array());
    }
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function get_by_cid($cid)
    {
        Database::query('
			SELECT 
				bp.*,
				c.created,
				c.updated
			FROM {blog_posts} bp
				JOIN {content} c ON c.id = bp.cid
			WHERE bp.cid = %s
				AND c.site_cid = %s
			', 
			$cid,
			User::current_site()
		);

        return self::_get_categories(Database::fetch_array());
    }
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function create($title, $content, $slug, $published)
    {
		$cid = Content::create(BLOG_TYPE_POST);

		$status = Database::insert('blog_posts', array(
			'cid' => $cid,
			'title' => $title,
			'content' => $content,
			'slug' => $slug,
			'published' => $published
		));

		if($status)
			return $cid;
		return false;
    }

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function add_to_category($cid, $category_cid)
	{
		if(is_array($category_cid))
		{
			foreach($category_cid as $cat_cid)
				if(!self::add_to_category($cid, $cat_cid))
					return false;

			return true;
		}
		else
		{
			return Database::insert('blog_post_categories', array(
				'post_cid' => $cid,
				'category_cid' => $category_cid
			));
		}
	}
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function update($cid, $title, $content, $slug, $published)
    {
		Content::update($cid);

		Database::update('blog_posts',
			array(
				'title' => $title,
				'content' => $content,
				'slug' => $slug,
				'published' => $published
			),
			array('cid' => $cid)
		);
    }

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function update_categories($cid, $category_id)
	{
		Database::delete('blog_post_categories', array('post_cid' => $cid));
		return self::add_to_category($cid, $category_id);
	}
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function delete($cid)
    {
		// Always make sure this cid exists on our site, avoid hacks
		if(self::get_by_cid($cid))
		{
			Content::delete($cid);
			Database::delete('blog_post_categories', array('post_cid' => $cid));
			Database::delete('blog_posts', array('cid' => $cid));
			
			return true;
		}

		return false;
    }

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	private static function _get_categories($rows)
	{
		// Check if we are getting multiple rows
		if(isset($rows[0]))
		{
			foreach($rows as &$row)
				$row['categories'] = Blog_Model_Categories::get_all_by_post_cid(
					$row['cid']);
		}
		elseif($rows)
			$rows['categories'] = Blog_Model_Categories::get_all_by_post_cid($rows['cid']);

		return $rows;
	}

}
