<?php 
/**
 * =============================================================================
 * Twitter
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class Twitter {

	/**
	 * -------------------------------------------------------------------------
	 * Returns the most recent tweets for a user in an array.
	 *
	 * @param $username
	 *		The twitter username to get tweets for.
	 *
	 * @param $limit
	 *		An optional parameter to specify how many tweets to return
	 *		This defaults to 5 if not set.
	 * -------------------------------------------------------------------------
	 */
	public static function get_recent($username, $limit = 5, $format = 'json')
	{
		$tweets = json_decode(file_get_contents(
			sprintf(
				'http://api.twitter.com/1/statuses/user_timeline/%s.%s',
				$username, $format
			)
		));

		if(count($tweets) > $limit)
			$tweets = array_slice($tweets, 0, $limit);

		return $tweets;
	}

}
