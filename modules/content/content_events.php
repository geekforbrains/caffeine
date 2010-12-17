<?php
/**
 * =============================================================================
 * Content_Events
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
final class Content_Events {


	/**
	 * -------------------------------------------------------------------------
	 * Implements the Database::install event.
	 * -------------------------------------------------------------------------
	 */
	public static function database_install()
	{
		return array(
			'content_types' => array (
				'fields' => array(
					'id' => array(
						'type' => 'auto increment',
						'unsigned' => true,
						'not null' => true
					),
					'type' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					)
				),

				'indexes' => array(
					'type' => array('type')
				),

				'primary key' => array('id')
			),

			'content' => array(
				'fields' => array(
					'id' => array(
						'type' => 'auto increment',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'parent_id' => array(
						'type' => 'int',
						'size' => 'normal',
						'unsigned' => true,
						'not null' => true
					),
					'type_id' => array(
						'type' => 'int',
						'size' => 'normal',
						'unsigned' => true,
						'not null' => true
					),
					'site_id' => array(
						'type' => 'int',
						'size' => 'normal',
						'unsigned' => true,
						'not null' => true
					),
					'user_id' => array(
						'type' => 'int',
						'size' => 'normal',
						'unsigned' => true,
						'not null' => true
					),
					'created' => array(
						'type' => 'int',
						'size' => 'normal',
						'unsigned' => true,
						'not null' => true
					),
					'updated' => array(
						'type' => 'int',
						'size' => 'normal',
						'unsigned' => true,
						'not null' => true
					)
				),

				'indexes' => array(
					'parent_id' => array('parent_id'),
					'type_id' => array('type_id'),
					'site_id' => array('site_id'),
					'user_id' => array('user_id')
				),

				'primary key' => array('id')
			)
						
		);
	}

}
