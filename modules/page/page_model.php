<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Page_Model
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class Page_Model {
	
	/**
	 * -------------------------------------------------------------------------
	 * Returns an array of all available pages. This is just a straight return
	 * from the database. They aren't sorted by parent.
	 *
	 * @param $published
	 *		Determines to get published, or un-published posts. By default this
	 *		method will return all pages, regardless of publish status. Set
	 *		to "1" for published pages and "0" for draft pages.
	 *
	 * @return array
	 *		An associative array of pages.
	 * -------------------------------------------------------------------------
	 */
	public static function get_all($published = null)
	{
		if(!is_null($published))
		{
			Database::query('
				SELECT 
					p.*,
					c.created,
					c.updated
				FROM {pages} p 
					JOIN {content} c ON c.id = p.cid
				WHERE published = %s 
					AND c.site_cid = %s
				ORDER BY p.title ASC', 
				$published,
				User::current_site()
			);
		}
		else
		{
			Database::query('
				SELECT 
					p.*,
					c.created,
					c.updated
				FROM {pages} p
					JOIN {content} c ON c.id = p.cid
				WHERE c.site_cid = %s
				ORDER BY p.title ASC
				',
				User::current_site()
			);
		}

		return Database::fetch_all();
	}

	/**
	 * -------------------------------------------------------------------------
	 * Gets a page based on its ID. This type of method is mostly used in the
	 * Admin area, as all front-pages would use the "get_by_slug" method.
	 *
	 * @param $page_id
	 *		The ID of the page to get.
	 *
	 * @return mixed
	 *		If a page with the given ID exists, a single associative array
	 *		is returned. Otherwise boolean false is returned.
	 * -------------------------------------------------------------------------
	 */
	public static function get_by_cid($cid) 
	{
		Database::query('
			SELECT 
				p.*,
				c.created,
				c.updated
			FROM {pages} p
				JOIN {content} c ON c.id = p.cid
			WHERE p.cid = %s
				AND c.site_cid = %s
			', 
			$cid,
			User::current_site()
		);

		if(Database::num_rows() > 0)
			return Database::fetch_array();

		return false;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Gets all pages who's parent ID is the given parameter.
	 *
	 * @param $parent_cid
	 *		The parent ID a page must be associated with.
	 *
	 * @return array
	 *		Returns an array of pages, or an empty array.
	 * -------------------------------------------------------------------------
	 */
	public static function get_by_parent_cid($parent_cid)
	{
		Database::query('
			SELECT 
				p.*,
				c.created,
				c.updated
			FROM {pages} p
				JOIN {content} c ON c.id = p.cid
			WHERE p.parent_cid = %s
				AND c.site_cid = %s
			', 
			$parent_cid,
			User::current_site()
		);

		return Database::fetch_all();
	}

	/**
	 * -------------------------------------------------------------------------
	 * Gets a page based on its slug. A "slug" is a unique key based on the
	 * the title. This is meant to help with SEO by providing "pretty" urls.
	 *
	 * @param $page_slug
	 *		The slug of the page to get.
	 *
	 * @param $published
	 *		Determines whether to get published or un-published post. This will
	 *		almost always be "1" because draft posts should never be accessed
	 *		via their slug, but rather their ID. Still, flexibility is nice.
	 *
	 * @return mixed.
	 *		If a page with the given slug exists, a single associative array
	 *		is returned. Otherwise boolean false is returned.
	 * -------------------------------------------------------------------------
	 */
	public static function get_by_slug($slug, $published = 1) 
	{
		Database::query('
			SELECT 
				p.*,
				c.created,
				c.updated
			FROM {pages} p
				JOIN {content} c ON c.id = p.cid
			WHERE p.slug LIKE %s 
				AND p.published = %s
				AND c.site_cid = %s
			', 
			$slug, 
			$published,
			User::current_site()
		);

		if(Database::num_rows() > 0)
			return Database::fetch_array();

		return false;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Gets all pages who's parent matches the given slug.
	 *
	 * @param $slug
	 *		The parent slug to get child pages for.
	 *
	 * @return array
	 *		Returns an array of pages if they exist, otherwise an empty array
	 *		is returned.
	 * -------------------------------------------------------------------------
	 */
	public static function get_by_parent_slug($slug)
	{
		Database::query('
			SELECT
				p1.*,
				c.created,
				c.updated
			FROM {pages} p1
				JOIN {content} c ON c.id = p1.cid
				JOIN {pages} p2 ON p2.cid = p1.parent_cid 
			WHERE
				p2.slug = %s
				AND c.site_cid = %s
			', 
			$slug,
			User::current_site()
		);

		return Database::fetch_all();
	}

	/**
	 * -------------------------------------------------------------------------
	 * Adds a new page.
	 *
	 * @param $parent_id
	 *		The parent page to associate this page with. Set to 0 if it isn't
	 *		associated with a parent.
	 *
	 * @param $site_id
	 *		The site ID this page is created and shown on.
	 *
	 * @param $user_id
	 *		The user ID who created this page.
	 *		
	 * @param $title
	 *		The title of the page.
	 *
	 * @param $slug
	 *		The slug of the page, created from the title.
	 *		@see The String::tagify method.
	 *
	 * @param $content
	 *		The content of the page.
	 *
	 * @return boolean
	 *		Returns true if the creation was successful. False otherwise.
	 * -------------------------------------------------------------------------
	 */
	public static function add($parent_cid, $title, $slug, $content, $published)
	{
		$cid = Content::create(PAGE_TYPE);

		return Database::insert('pages', array(
			'cid' => $cid,
			'parent_cid' => $parent_cid,
			'title' => $title,
			'slug' => $slug,
			'content' => $content,
			'published' => $published
		));
	}

	/**
	 * -------------------------------------------------------------------------
	 * Updates a page based on its ID.
	 *
	 * @param $page_id
	 *		The ID of the page to update.
	 *
	 * @param $parent_id
	 *		The parent ID to associate this page with. Set to 0 to make it
	 *		a parent page.
	 *
	 * @param $title
	 *		The title of the page.
	 *
	 * @param $slug
	 *		The slug of the page, based on the title.
	 *
	 * @param $content
	 *		The content of the page.
	 *
	 * @return boolean
	 *		Returns true if the update was successful. False otherwise.
	 * -------------------------------------------------------------------------
	 */
	public static function update($cid, $parent_cid, $title, $slug, $content, $published)
	{
		Content::update($cid);

		Database::update('pages',
			array(
				'parent_cid' => $parent_cid,
				'title' => $title,
				'slug' => $slug,
				'content' => $content,
				'published' => $published
			),
			array('cid' => $cid)
		);
	}
	
	/**
	 * -------------------------------------------------------------------------
	 * Deletes a page based on its ID.
	 *
	 * @param $page_id
	 *		The ID of the page to delete.
	 *
	 * @return boolean
	 *		Returns true if the page was deleted successfully. False otherwise.
	 * -------------------------------------------------------------------------
	 */
	public static function delete($cid) 
	{
		if(self::get_by_cid($cid))
		{
			// Update all child pages to have no parent
			Database::update('pages', array('parent_cid' => 0),
				array('parent_cid' => $cid));

			Content::delete($cid);
			Database::delete('pages', array('cid' => $cid));
			
			return true;
		}

		return false;
	}

}
