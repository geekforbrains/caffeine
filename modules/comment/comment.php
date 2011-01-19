<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Comment
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class Comment {
	
	/**
	 * -------------------------------------------------------------------------
	 * Creates a new comment, related to the given content ID.
	 *
	 * @param $relative_cid
	 *		The relative content ID the comment will be associated with.
	 *
	 * @param $author
	 *		The name of the author creating the comment.
	 *
	 * @param $email
	 *		The authors email address.
	 *
	 * @param $website
	 *		The authors website.
	 *
	 * @param $comment
	 *		The authors comment.
	 *
	 * @return mixed
	 *		Returns the content ID of the comment, if it was created 
	 *		successfully. False otherwise.
	 * -------------------------------------------------------------------------
	 */
	public static function create($relative_cid, $author, $email, $website, $comment)
	{
		$cid = Content::create(COMMENT_TYPE);

		$status = Database::insert('comments', array(
			'relative_cid' => $relative_cid,
			'author' => $author,
			'email' => $email,
			'website' => $website,
			'comment' => $comment
		));

		if($status)
			return $cid;
		return false;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Deletes a comment based on its ID.
	 *
	 * @param $cid
	 *		The content ID of the comment to be deleted.
	 * 
	 * @return boolean
	 *		Returns true if the deletion was successful. False otherwise.
	 * -------------------------------------------------------------------------
	 */
	public static function delete($cid)
	{
		Content::delete($cid);
		return Database::delete('comments', array('cid' => $cid));
	}

	public static function get_all($relative_cid) {}

	public static function get_all_by_type($content_type) {}

	public static function get_by_cid($cid) {}

}
