<?php
final class SEO_Events {

	public static function caffeine_init() {
		SEO::check_path();
	}

	public static function view_block_paths()
	{
		return array(
			'SEO' => CAFFEINE_MODULES_PATH . 'seo/blocks/',
			'SEO_Admin' => CAFFEINE_MODULES_PATH . 'seo/blocks/admin/'
		);
	}

	public static function path_callbacks()
	{
		return array(
			'admin/seo' => array(
				'title' => 'SEO',
				'alias' => 'admin/seo/manage'
			),
			'admin/seo/manage' => array(
				'title' => 'Manage Paths',
				'callback' => array('SEO_Admin', 'manage'),
				'auth' => 'manage seo',
				'visible' => true
			),
			'admin/seo/create' => array(
				'title' => 'Create Path',
				'callback' => array('SEO_Admin', 'create'),
				'auth' => 'create seo',
				'visible' => true
			),
			'admin/seo/edit/%d' => array(
				'title' => 'Edit Path',
				'callback' => array('SEO_Admin', 'edit'),
				'auth' => 'edit seo',
				'visible' => true
			),
			'admin/seo/delete/%d' => array(
				'callback' => array('SEO_Admin', 'delete'),
				'auth' => 'delete seo',
				'visible' => true
			),
			'admin/seo/analytics' => array(
				'title' => 'Analytics',
				'callback' => array('SEO_Admin', 'analytics'),
				'auth' => 'manage seo',
				'visible' => true
			)
		);
	}

	public static function database_install()
	{
		return array(
			'seo' => array(
				'fields' => array(
					'cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'path' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					),
					'title' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					),
					'meta_author' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					),
					'meta_description' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					),
					'meta_keywords' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					),
					'meta_robots' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					)
				),

				'primary key' => array('cid')
			),

			'seo_analytics' => array(
				'fields' => array(
					'cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'code' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					)
				),

				'primary key' => array('cid')
			)
		);
	}

}
