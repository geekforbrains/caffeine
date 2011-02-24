<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
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
			'content' => array(
				'fields' => array(
					'id' => array(
						'type' => 'auto increment',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'type' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					),
					'site_cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'user_cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'created' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'updated' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					)
				),

				'indexes' => array(
					'type' => array('type'),
					'site_cid' => array('site_cid'),
					'user_cid' => array('user_cid'),
					'created' => array('created'),
					'updated' => array('updated')
				),

				'primary key' => array('id')
			),

			// Temporary addition of relative methods
			// This will be removed, dont use it
			'content_relatives' => array(
				'fields' => array(
					'cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'relative_cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					)
				),

				'indexes' => array(
					'cid' => array('cid'),
					'relative_cid' => array('relative_cid')
				)
			)		
		);
	}

}
