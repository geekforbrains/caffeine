<?php
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
		return array(
			// Front
			'page/%s' => array(
				'callback' => array('Page', 'load'),
				'auth' => true
			),

			// Admin
			'admin/page' => array(
				'title' => 'Pages',
				'callback' => array('Page_Admin', 'manage'),
				'auth' => 'manage pages'
			),
			'admin/page/manage' => array(
				'title' => 'Manage Pages',
				'callback' => array('Page_Admin', 'manage'),
				'auth' => 'manage pages'
			),
			'admin/page/create' => array(
				'title' => 'Create Page',
				'callback' => array('Page_Admin', 'create'),
				'auth' => 'create pages'
			),
			'admin/page/edit/%d' => array(
				'callback' => array('Page_Admin', 'edit'),
				'auth' => 'edit pages',
				'visible' => false
			),
			'admin/page/delete/%d' => array(
				'callback' => array('Page_Admin', 'delete'),
				'auth' => 'delete pages',
				'visible' => false
			)
		);
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
					)
				),

				'indexes' => array(
					'slug' => array('slug')
				),

				'primary key' => array('cid')
			)
		);
	}

}
