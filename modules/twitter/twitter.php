<?php 
/**
 * =============================================================================
 * Twitter
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
require_once(CAFFEINE_MODULES_PATH . 'twitter/twitteroauth/twitteroauth.php');
class Twitter {

	/**
	 * -------------------------------------------------------------------------
	 * Redirects the user to the Twitter application allow page. This is the 
	 * starting point for enabling Twitter.
	 * -------------------------------------------------------------------------
	 */
	public static function activate()
	{
		$conn = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET);
		$token = $conn->getRequestToken(Router::full_url('twitter/callback/%d', User::get('cid')));

		$_SESSION['oauth_token'] = $token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $token['oauth_token_secret'];

		Router::redirect($conn->getAuthorizeURL($token));
	}

	/**
	 * -------------------------------------------------------------------------
	 * Used to disable Twitter for the current user. Simply deletes the tokens.
	 * -------------------------------------------------------------------------
	 */
	public static function disable()
	{
		Twitter_Model::delete(User::get('cid'));
		Message::store(MSG_OK, 'Twitter account disabled successfully.');
		Router::redirect('admin/social/settings');
	}
	
	/**
	 * -------------------------------------------------------------------------
	 * The callback after Twitter::activate(). This is where the user is
	 * redirected to after allowing the application on Twitter. Here we store
	 * the Twitter tokens and the current user id then redirect with a message.
	 * -------------------------------------------------------------------------
	 */
	public static function callback($user_cid)
	{
		if(isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token'])
			Message::store(MSG_ERR, 'Twitter session expired. Please retry activation.');
		else
		{
			$conn = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

			// Store these values in the db
			$token = $conn->getAccessToken();
			Twitter_Model::update($user_cid, $token);
			
			// Redirect to social admin
			Message::store(MSG_OK, 'Twitter account for "' .$token['screen_name']. '" was setup successfully.');
		}

		Router::redirect('admin/social/settings');
	}

	/**
	 * -------------------------------------------------------------------------
	 * Used to post a message to Twitter. This will use the Twitter credentials
	 * for the current account. If non is configured, boolean false is returned.
	 * -------------------------------------------------------------------------
	 */
	public static function post($message)
	{
		$account = Twitter_Model::get(User::get('cid'));

		if($account)
		{
			// Make below its own method
			$conn = new TwitterOAuth(
				TWITTER_CONSUMER_KEY,
				TWITTER_CONSUMER_SECRET,
				$account['token']['oauth_token'],
				$account['token']['oauth_token_secret']
			);

			$result = $conn->post('statuses/update', array('status' => $message));

			$http_code = $conn->http_code;
			if($http_code == 200)
				return true;
			else
				return false;
				//echo 'Error: ' . $result->error;
		}

		return false;
	}

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

	/**
	 * -------------------------------------------------------------------------
	 * Posts a message to Twitter for the current user account.
	 * DEPRECATED: Twitter doesn't support basic auth anymore!!!
	 * -------------------------------------------------------------------------
	public static function post($message)
	{
		$login = Twitter_Model::get_login(User::get('cid'));

		if($login)
		{
			$username = $login['username'];
			$password = $login['password'];

			$url = 'http://twitter.com/statuses/update.json';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "status=$message");
			curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

			$buffer = curl_exec($ch);

			if(!empty($buffer))
				die($buffer);
		}

		return false;
	}
	*/

}
