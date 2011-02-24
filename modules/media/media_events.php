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
			// Display original image
			'media/image/%d' => array(
				'callback' => array('Media_Display', 'image'),
				'auth' => true,
				'visible' => false
			),

			// Display original image, rotated
			'media/image/%d/%d' => array(
				'callback' => array('Media_Display', 'image'),
				'auth' => true,
				'visible' => false
			),

			// Resize by percent
			'media/image/%d/%d/%d' => array(
				'callback' => array('Media_Display', 'image'),
				'auth' => true,
				'visible' => false
			),

			// Display image with width and height specified
			// If both width and height are greater than 0, adaptive resize will be used
			// If width or height is 0, the other will be used for resize
			'media/image/%d/%d/%d/%d' => array(
				'callback' => array('Media_Display', 'image'),
				'auth' => true,
				'visible' => false
			),

			'admin/media/dialog/%s' => array(
				'title' => 'Media Dialog',
				'callback' => array('Media_Display', 'dialog'),
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
					'hash' => array(
						'type' => 'varchar',
						'length' => 32,
						'not null' => true
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
					),
					'exif' => array(
						'type' => 'text',
						'size' => 'normal',
						'not null' => true
					)
				),
				
				'indexes' => array(
					'hash' => array('hash')
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
