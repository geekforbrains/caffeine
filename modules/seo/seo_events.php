<?php
final class SEO_Events {

	public static function caffeine_init() {
		SEO::check_path();
	}

	public static function view_block_paths() {
		return array('SEO_Admin' => CAFFEINE_MODULES_PATH . 'seo/blocks/');
	}

	public static function path_callbacks()
	{
		return array(
			'admin/seo' => array(
				'title' => 'SEO',
				'alias' => 'admin/seo/manage'
			),
			'admin/seo/manage' => array(
				'title' => 'Manage SEO',
				'callback' => array('SEO_Admin', 'manage'),
				'auth' => 'manage seo',
				'visible' => true
			),
			'admin/seo/create' => array(
				'title' => 'Create SEO',
				'callback' => array('SEO_Admin', 'create'),
				'auth' => 'create seo',
				'visible' => true
			),
			'admin/seo/edit/%d' => array(
				'title' => 'Edit SEO',
				'callback' => array('SEO_Admin', 'edit'),
				'auth' => 'edit seo',
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
			)
		);
	}

}
