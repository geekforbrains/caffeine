<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Content
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 *
 * @event created
 *		Called when new content is created. 
 *
 * @event updated
 *		Called when content is updated.
 *
 * @event deleted
 *		Called when content is deleted.
 * =============================================================================
 */
class Content {

	// Keep track of deletions to avoid re-running
	private static $_deletions = array();

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
		{
			$cid = Database::insert_id();
			Caffeine::trigger('Content', 'created', array('cid' => $cid));
			return $cid;
		}

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
		if(self::exists($cid))
		{
			$status = Database::update('content',
				array('updated' => time()),
				array('id' => $cid)
			);

			if($status)
			{
				Caffeine::trigger('Content', 'updated', array('cid' => $cid));
				return true;
			}
		}

		return false;
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
	public static function delete($cid) 
	{
		$status = false;

		if(isset(self::$_deletions[$cid]))
			return self::$_deletions[$cid];

		if(self::exists($cid))
		{
			if(Database::delete('content', array('id' => $cid)))
			{
				Caffeine::trigger('Content', 'deleted', array('cid' => $cid));
				$status = true;
			}
		}

		self::$_deletions[$cid] = $status;
		return $status;
	}	

	/**
	 * -------------------------------------------------------------------------
	 * Determines if content exists by its id. Mainly used to ensure we dont 
	 * enter into an infinite loop when other areas of the application make use
	 * of the "created", "updated" and "deleted" events.
	 *
	 * @param $cid
	 *		The ID to check for existance.
	 *
	 * @return boolean
	 *		Returns true if the ID exists, false otherwise.
	 * -------------------------------------------------------------------------
	 */
	public static function exists($cid)
	{
		Database::query('SELECT id FROM {content} WHERE id = %s', $cid);

		if(Database::num_rows() > 0)
			return true;
		return false;
	}

}
