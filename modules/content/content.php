<?php
/**
 * =============================================================================
 * Content
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class Content extends Database {

	/**
	 * -------------------------------------------------------------------------
	 * Creates and returns a new ID for the given content type. A second param
	 * can be passed to associate the ID with another content ID. This allows
	 * for linking of content types, such as comments associated with a blog
	 * post.
	 *
	 * @param $type
	 *		The type to associate the generated ID with.
	 *
	 * @param $parent_id
	 *		An optional content ID to associate the newly generated ID with.
	 *
	 * @return int
	 *		Returns a newly generated content ID.
	 * -------------------------------------------------------------------------
	 */
	public static function get_id($type, $parent_id = 0) 
	{
		$user = User::get_current();	

		self::query('
			INSERT INTO {content} (
				parent_id,
				type_id,
				site_id,
				user_id
				created,
				updated
			) VALUES (
				%s, %s, %s, %s, %s
			)', 
			$parent_id,
			self::_get_type_id($type),
			$user['site_id'],
			$user['id'],
			time(),
			time()
		);

		return self::insert_id();
	}

	/**
	 * -------------------------------------------------------------------------
	 * Gets the ID for the given type. If the type doesn't exist, its created
	 * and the new ID is returned.
	 *
	 * @param $type
	 *		The content type to get an ID for.
	 *
	 * @return int
	 *		Returns the ID of the given content type.
	 * -------------------------------------------------------------------------
	 */
	private static function _get_type_id($type)
	{
		self::query('SELECT id FROM {content_types} WHERE type = %s', $type);

		if(self::num_rows() > 0)
			return self::fetch_single('id');
		else
		{
			self::query('INSERT INTO {content_types} (type) VALUES (%s)', $type);
			return self::insert_id();
		}
	}

}
