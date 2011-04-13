<?php 
class Twitter_Model {

	public static function get($user_cid)
	{
		Database::query('SELECT * FROM {twitter_accounts} WHERE user_cid = %s', $user_cid);

		if(Database::num_rows() > 0)
		{
			$row = Database::fetch_array();
			$row['token'] = unserialize($row['token']);
			return $row;
		}

		return false;
	}
	
	public static function update($user_cid, $token)
	{
		// Clear previous account if any
		self::delete($user_cid);

		// Insert new settings
		return Database::insert('twitter_accounts', array(
			'user_cid' => $user_cid,
			'token' => serialize($token)
		));
	}

	public static function delete($user_cid)
	{
		Database::delete('twitter_accounts', array('user_cid' => $user_cid));
	}

}
