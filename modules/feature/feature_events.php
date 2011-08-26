<?php
final class Feature_Events {
	
	public static function path_callbacks()
	{
		return array(
			// Features
			'admin/feature' => array(
				'title' => 'Features',
				'alias' => 'admin/feature/manage'
			),
			'admin/feature/manage' => array(
				'title' => 'Features',
				'callback' => array('Feature_Admin', 'manage'),
				'auth' => 'manage features',
			),
			// %d = area id
			'admin/feature/edit/%d' => array(
				'title' => 'Edit Feature',
				'callback' => array('Feature_Admin', 'edit'),
				'auth' => 'edit features'
			),
			// %d1 = area_id, %d2 = feature_id
			'admin/feature/edit/%d/%d' => array(
				'title' => 'Edit Features',
				'callback' => array('Feature_Admin', 'edit'),
				'auth' => 'edit features'
			),
			'admin/feature/edit/%d/%d/delete-image/%d' => array(
				'callback' => array('Feature_Admin', 'delete_image'),
				'auth' => 'delete feature images'
			),
            'admin/feature/delete/%d/%d' => array(
                'callback' => array('Feature_Admin', 'delete'),
                'auth' => 'delete features'
            ),

			// Areas
			'admin/feature/area' => array(
				'title' => 'Areas',
				'alias' => 'admin/feature/area/manage'
			),
			'admin/feature/area/manage' => array(
				'title' => 'Manage Areas',
				'callback' => array('Feature_Admin_Area', 'manage'),
				'auth' => 'manage areas'
			),
			'admin/feature/area/create' => array(
				'title' => 'Create Area',
				'callback' => array('Feature_Admin_Area', 'create'),
				'auth' => 'create areas'
			),
			'admin/feature/area/edit/%d' => array(
				'title' => 'Edit Area',
				'callback' => array('Feature_Admin_Area', 'edit'),
				'auth' => 'edit areas'
			),
			'admin/feature/area/delete/%d' => array(
				'callback' => array('Feature_Admin_Area', 'delete'),
				'auth' => 'delete areas'
			)
		);
	}

	public static function database_install()
	{
		return array(
			'feature_areas' => array(
				'fields' => array(
					'cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'tag' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					),
					'name' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					),
					'has_title' => array(
						'type' => 'int',
						'size' => 'tiny',
						'not null' => true
					),
					'has_body' => array(
						'type' => 'int',
						'size' => 'tiny',
						'not null' => true
					),
					'has_link' => array(
						'type' => 'int',
						'size' => 'tiny',
						'not null' => true
					),
					'has_image' => array(
						'type' => 'int',
						'size' => 'tiny',
						'not null' => true
					),
					'image_width' => array(
						'type' => 'int',
						'size' => 'normal',
						'unsigned' => true,
						'not null' => true
					),
					'image_height' => array(
						'type' => 'int',
						'size' => 'normal',
						'unsigned' => true,
						'not null' => true
					),
					'multiple_features' => array(
						'type' => 'int',
						'size' => 'tiny',
						'not null' => true
					),
					'multiple_images' => array(
						'type' => 'int',
						'size' => 'tiny',
						'not null' => true
					)
				),

				'indexes' => array(
					'tag' => array('tag'),
					'has_title' => array('has_title'),
					'has_body' => array('has_body'),
					'has_link' => array('has_link'),
					'has_image' => array('has_image'),
					'multiple_features' => array('multiple_features'),
					'multiple_images' => array('multiple_images')
				),

				'primary key' => array('cid')
			),

			'features' => array(
				'fields' => array(
					'cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'area_cid' => array(
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
					'body' => array(
						'type' => 'text',
						'size' => 'normal',
						'not null' => true
					),
					'link' => array(
						'type' => 'text',
						'size' => 'tiny',
						'not null' => true
					)
				),

				'indexes' => array(
					'area_cid' => array('area_cid')
				),

				'primary key' => array('cid')
			),

			'feature_images' => array(
				'fields' => array(
					'feature_cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'media_cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					)
				),

				'indexes' => array(
					'feature_cid' => array('feature_cid'),
					'media_cid' => array('media_cid')
				)
			)
		);
	}

}
