<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Media_Events
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
final class Media_Events {
	
	/**
	 * -------------------------------------------------------------------------
	 * Implements the View::block_paths event.
	 * -------------------------------------------------------------------------
	 */
	public static function view_block_paths() {
		return array('Media' => CAFFEINE_MODULES_PATH . 'media/blocks/');
	}

	/**
	 * -------------------------------------------------------------------------
	 * Implements the Path:callbacks event.
	 * -------------------------------------------------------------------------
	 */
	public static function path_callbacks()
	{
		return array(
			'admin/media/dialog/%s' => array(
				'title' => 'Media Dialog',
				'callback' => array('Media', 'dialog'),
				'auth' => true,
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
			'media_files' => array(
				'fields' => array(
					'cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'name' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true,
					),
					'path' => array(
						'type' => 'text',
						'size' => 'tiny',
						'not null' => true,
					),
					'type' => array(
						'type' => 'varchar',
						'length' => 125,
						'not null' => true
					),
					'size' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					)
				),

				'primary key' => array('cid')
			),

			'media_urls' => array(
				'fields' => array(
					'cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'url' => array(
						'type' => 'text',
						'size' => 'normal',
						'not null' => true
					)
				),

				'primary key' => array('cid')
			)
		);
	}

}
