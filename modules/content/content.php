<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Content
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class Content {

	/**
	 * -------------------------------------------------------------------------
	 * Creates and returns a new ID for the given content type.
	 *
	 * @param $type
	 *		The type to associate the generated ID with.
	 *
	 * @return int
	 *		Returns a newly generated content ID.
	 * -------------------------------------------------------------------------
	 */
	public static function create($type) 
	{
		$user = User::get_current();	
		$timestamp = time();

		$status = Database::insert('content',	array(
			'type' => $type,
			'site_id' => $user['site_id'],
			'user_id' => $user['id'],
			'created' => $timestamp,
			'updated' => $timestamp
		));

		if($status)
			return Database::insert_id();
		return false;
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
		return Database::update('content',
			array('updated' => time()),
			array('id' => $cid)
		);
	}

	/**
	 * -------------------------------------------------------------------------
	 * Deletes a content record based on its id.
	 *
	 * @param $cid
	 *		The ID of the content to be deleted.
	 *
	 * @return boolean
	 *		Returns true if the ID existed and it was deleted successfully.
	 *		False otherwise.
	 * -------------------------------------------------------------------------
	 */
	public static function delete($cid) {
		return Database::delete('content', array('id' => $cid));
	}	

}
