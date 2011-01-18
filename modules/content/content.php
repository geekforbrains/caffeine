<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
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
	 * can be passed to associate the ID with another content ID.
	 *
	 * @see Content::_add_relatives()
	 *
	 * @param $type
	 *		The type to associate the generated ID with.
	 *
	 * @param $relatives
	 *		An optional content ID or array of ID's to associate this content
	 *		type with. These are called "relatives". The type being created is
	 *		the "parent" and the ID's being associated with it are the
	 *		"children".
	 *
	 * @return int
	 *		Returns a newly generated content ID.
	 * -------------------------------------------------------------------------
	 */
	public static function create($type, $relatives = null) 
	{
		$user = User::get_current();	
		$timestamp = time();

		self::query('
			INSERT INTO {content} (
				type,
				site_id,
				user_id,
				created,
				updated
			) VALUES (
				%s, %s, %s, %s, %s
			)', 
			$type,
			$user['site_id'],
			$user['id'],
			$timestamp,
			$timestamp
		);
		
		$cid = self::insert_id();
	
		if(!is_null($relatives) && $relatives) // Second check is for 0 (zero)
			self::_add_relatives($cid, $relatives);

		return $cid;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Updates the given content ID's "update" field to the current timestamp.
	 *
	 * @param $cid
	 *		The ID of the content to update.
	 *
	 * @return boolean
	 *		Returns true if the ID exists and was updated successfully. False
	 *		otherwise.
	 * -------------------------------------------------------------------------
	 */
	public static function update($cid)
	{
		self::query('UPDATE {content} SET updated = %s WHERE id = %s',
			time(), $cid);

		if(self::affected_rows() > 0)
			return true;

		return false;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Deletes a content record based on its id. Also deletes an records of 
	 * associated content (relatives).
	 *
	 * @param $cid
	 *		The ID of the content to be deleted.
	 *
	 * @return boolean
	 *		Returns true if the ID existed and it was deleted successfully.
	 *		False otherwise.
	 * -------------------------------------------------------------------------
	 */
	public static function delete($cid)
	{
		self::query('DELETE FROM {content_relatives} WHERE cid = %s', $cid);
		self::query('DELETE FROM {content} WHERE id = %s', $cid);

		if(self::affected_rows() > 0)
			return true;

		return false;
	}	

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function relative_exists($cid, $relative_cid)
	{
		self::query('SELECT cid FROM {content_relatives} WHERE
			cid = %s AND relative_cid = %s', $cid, $relative_cid);

		if(self::num_rows() > 0)
			return true;
		return false;
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function add_relative($cid, $relative_cid)
	{
		self::query('SELECT * FROM {content_relatives} WHERE
			cid = %s AND relative_cid = %s', $cid, $relative_cid);

		if(self::num_rows() == 0)
		{
			self::query('INSERT INTO {content_relatives} (cid, relative_cid)
				VALUES (%s, %s)', $cid, $relative_cid);

			if(self::affected_rows() > 0)
				return true;
			return false;
		}

		return true;
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function remove_relative($cid, $relative_cid)
	{
		self::query('DELETE FROM {content_relatives} WHERE
			cid = %s AND relative_cid = %s', $cid, $relative_cid);

		if(self::affected_rows() > 0)
			return true;
		return false;
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function update_relatives($cid, $relatives)
	{
		self::query('DELETE FROM {content_relatives} WHERE cid = %s', $cid);
		self::_add_relatives($cid, $relatives);
	}

	/**
	 * -------------------------------------------------------------------------
	 * Adds relative content to the given content ID. This is used to associate
	 * other types of content togeather. For example, a Blog post might have
	 * several categories associated with it.
	 *
	 * @param $cid
	 *		The main content ID to have the other ID's associated with.
	 *
	 * @param $relatives
	 *		A single ID or an array of ID's to be associated with $cid.
	 * -------------------------------------------------------------------------
	 */
	private static function _add_relatives($cid, $relatives)
	{
		if(is_array($relatives))
		{
			foreach($relatives as $r)
				self::_add_relatives($cid, $r);
		}
		else
		{
			self::query('INSERT INTO {content_relatives} (cid, relative_cid)
				VALUES (%s, %s)', $cid, $relatives);
		}
	}

}
