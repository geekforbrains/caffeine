<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Page
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class Page {

	/**
	 * -------------------------------------------------------------------------
	 * Redirects the user to the path specified in the page configuration file.
	 * This is used when accessing the page URL directly (foo.com/page)
	 * -------------------------------------------------------------------------
	 */
	public static function redirect() {
		Router::redirect(PAGE_REDIRECT);
	}

	/**
	 * -------------------------------------------------------------------------
	 * Loads a page based on its slug. If the slug doesn't exist, returns 
	 * boolean false to have the Path module load a 404 page.
	 * -------------------------------------------------------------------------
	 */
	public static function load() 
	{
		$path = Router::current_path();
		$path_bits = explode('/', $path);
		$slug = $path_bits[count($path_bits) - 1];

		$page = Page_Model::get_by_slug($slug);

		if($page)
			View::load('Page', 'page', array('page' => $page));
		else
			return false;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Build path information based on available pages.
	 * -------------------------------------------------------------------------
	 */
	public static function build_paths($paths = array(), $parent_cid = 0, $trail = 'page/')
	{
		$pages = Page_Model::get_by_parent_cid($parent_cid);
		
		foreach($pages as $page)
		{
			$paths[$trail . $page['slug']] = array(
				'title' => $page['title'],
				'callback' => array('Page', 'load'),
				'auth' => true,
				'visible' => true
			);

			// Process any child pages of this page
			$paths = self::build_paths($paths, $page['cid'], $trail . $page['slug'] . '/');
		}

		return $paths;
	}

}
