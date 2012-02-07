<?php

class Social_Twitter {

	/**
	 * Returns the most recent tweets for a user in an array.
	 *
	 * @param $username
	 *		The twitter username to get tweets for.
	 *
	 * @param $limit
	 *		An optional parameter to specify how many tweets to return
	 *		This defaults to 5 if not set.
	 */
	public function getRecent($username, $limit = 5, $format = 'json')
	{
		try
		{
			$url = sprintf('http://api.twitter.com/1/statuses/user_timeline/%s.%s', $username, $format);
			$get = file_get_contents($url);
		} 
		catch (Exception $e) 
		{
			$get = false;
		}
		
		if($get)
		{
			$tweets = json_decode($get);
			
			if(count($tweets) > $limit)
				$tweets = array_slice($tweets, 0, $limit);

			return $tweets;			
		}
		else
			return false;
	}

}
