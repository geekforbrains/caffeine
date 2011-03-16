<?php
final class SEO_Events {

	public static function caffeine_init() {
		SEO::check_path();
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
				'auth' => 'manage seo'
			),
			'admin/seo/create' => array(
				'title' => 'Create Path',
				'callback' => array('SEO_Admin', 'create'),
				'auth' => 'create seo'
			),
			'admin/seo/edit/%d' => array(
				'title' => 'Edit Path',
				'callback' => array('SEO_Admin', 'edit'),
				'auth' => 'edit seo'
			),
			'admin/seo/edit/%d/delete-meta/%d' => array(
				'callback' => array('SEO_Admin', 'delete_meta'),
				'auth' => 'delete meta'
			),
			'admin/seo/delete/%d' => array(
				'callback' => array('SEO_Admin', 'delete'),
				'auth' => 'delete seo'
			),
			'admin/seo/analytics' => array(
				'title' => 'Analytics',
				'callback' => array('SEO_Admin', 'analytics'),
				'auth' => 'manage seo'
			)
		);
	}

	public static function database_install()
	{
		return array(
			'seo_paths' => array(
				'fields' => array(
					'cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'path' => array(
						'type' => 'text',
						'size' => 'tiny',
						'not null' => true
					),
					'path_hash' => array(
						'type' => 'varchar',
						'length' => 32,
						'not null' => true
					),
					'title' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					),
					'prepend' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					),
					'append' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					),
					'is_default' => array(
						'type' => 'int',
						'size' => 'tiny',
						'not null' => true
					)
				),

				'indexes' => array(
					'path_hash' => array('path_hash'),
					'is_default' => array('is_default')
				),

				'primary key' => array('cid')
			),

			'seo_meta' => array(
				'fields' => array(
					'cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'seo_path_cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'name' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					),
					'content' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					),
					'is_httpequiv' => array(
						'type' => 'int',
						'size' => 'tiny',
						'not null' => true
					)
				),

				'indexes' => array(
					'seo_path_cid' => array('seo_path_cid'),
					'is_httpequiv' => array('is_httpequiv')
				)
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
