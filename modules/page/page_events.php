<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Page_Events
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
final class Page_Events {

	/**
	 * -------------------------------------------------------------------------
	 * Implements the View::block_paths event.
	 * -------------------------------------------------------------------------
	 */
	public static function view_block_paths() 
	{
		return array(
			'Page' => CAFFEINE_MODULES_PATH . 'page/blocks/',
			'Page_Admin' => CAFFEINE_MODULES_PATH . 'page/blocks/admin/'
		);
	}

	/**
	 * -------------------------------------------------------------------------
	 * Implements the Path::callbacks event.
	 * -------------------------------------------------------------------------
	 */
	public static function path_callbacks()
	{
		// For handeling redirects
		$paths['page'] = array(
			'title' => 'Pages',
			'callback' => array('Page', 'redirect'),
			'auth' => true,
			'visible' => true
		);

		// Front, dynamic pages
		$paths = Page::build_paths($paths);

		// Admin
		$paths['admin/page'] = array(
			'title' => 'Pages',
			'alias' => 'admin/page/manage'
		);

		$paths['admin/page/manage'] = array(
			'title' => 'Manage Pages',
			'callback' => array('Page_Admin', 'manage'),
			'auth' => 'manage pages'
		);

		$paths['admin/page/create'] = array(
			'title' => 'Create Page',
			'callback' => array('Page_Admin', 'create'),
			'auth' => 'create pages'
		);

		$paths['admin/page/edit/%d'] = array(
			'title' => 'Edit Page',
			'callback' => array('Page_Admin', 'edit'),
			'auth' => 'edit pages',
			'visible' => false
		);

		$paths['admin/page/delete/%d'] = array(
			'callback' => array('Page_Admin', 'delete'),
			'auth' => 'delete pages',
			'visible' => false
		);

		return $paths;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Implements the Database::install event.
	 * -------------------------------------------------------------------------
	 */
	public static function database_install()
	{
		return array(
			'pages' => array(
				'fields' => array(
					'cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'parent_cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'title' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					),
					'slug' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					),
					'content' => array(
						'type' => 'text',
						'size' => 'big',
						'not null' => true
					),
					'published' => array(
						'type' => 'int',
						'size' => 'tiny',
						'unsigned' => true,
						'not null' => true
					)
				),

				'indexes' => array(
					'parent_cid' => array('parent_cid'),
					'slug' => array('slug'),
					'published' => array('published')
				),

				'primary key' => array('cid')
			)
		);
	}

}
