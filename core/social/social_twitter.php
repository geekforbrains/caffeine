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

    /**
     * TODO
     */
    public function oauth()
    {
        Load::asset('social', 'twitteroauth.php');
        return new TwitterOAuth(Config::get('social.twitter_consumer_key'), Config::get('social.twitter_consumer_secret'));
    }

    /**
     * TODO
     */
    public function sendToTwitterForAuth()
    {
        $conn = $this->oauth();
        $request = $conn->getRequestToken(Config::get('social.twitter_callback_url'));

        $_SESSION['oauth_token'] = $request['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request['oauth_token_secret'];
         
        switch($conn->http_code)
        {
            case 200:
                $url = $conn->getAuthorizeURL($request['oauth_token']);
                Url::redirect($url);
                break;
            default:
                die('Could not connect to Twitter!'); // TODO Actual error page or message
        }
    }

}
