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
	 * Loads a page based on its slug. If the slug doesn't exist, returns 
	 * boolean false to have the Path module load a 404 page.
	 *
	 * @param $slug
	 *		The slug of the page to load.
	 * -------------------------------------------------------------------------
	 */
	public static function load($slug) 
	{
		$page = Page_Model::get_by_slug($slug);

		if($page)
			View::load('Page', 'page', array('page' => $page));
		else
			return false;
	}

}
