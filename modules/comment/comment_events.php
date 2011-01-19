<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Comment_Events
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
final class Comment_Events {

	/**
	 * -------------------------------------------------------------------------
	 * Implements the Content::deleted event.
	 *
	 * Used to delete any comments associated with other content being
	 * deleted. Helps avoid orphan records.
	 * -------------------------------------------------------------------------
	 */
	public static function content_deleted($data) {
		Comment::delete($data['cid']);
	}

	/** 
	 * -------------------------------------------------------------------------
	 * Implements the Database::install event.
	 * -------------------------------------------------------------------------
	 */
	public static function database_install()
	{
		return array(
			'comments' => array(
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
					),
					'author' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					),
					'email' => array(
						'type' => 'varchar',
						'length' => 255,
						'not null' => true
					),
					'website' => array(
						'type' => 'text',
						'size' => 'tiny',
						'not null' => true
					),
					'comment' => array(
						'type' => 'text',
						'size' => 'normal',
						'not null' => true
					)
				),

				'indexes' => array(
					'relative_cid' => array('relative_cid'),
				),

				'primary key' => array('cid')
			)
		);
	}

}
