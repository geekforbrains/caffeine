<?php
class Social_Admin {

	/**
	 * -------------------------------------------------------------------------
	 * Post status messages to Twitter and Facebook.
	 * -------------------------------------------------------------------------
	 */
	public static function post()
	{
		if($_POST)
		{
			Validate::check('message', 'Message', array('required'));

			if(Validate::passed())
			{
				if(isset($_POST['twitter']))
				{
					if(Twitter::post($_POST['message']))
						Message::set(MSG_OK, 'Message posted to Twitter successfully.');
					else
						Message::set(MSG_ERR, 'Error posting to Twitter. Please check your account details and try again.');
				}
			}
		}

		View::load('Social', 'admin/post', array(
			'twitter' => Twitter_Model::get(User::get('cid'))
		));
	}

	/**
	 * -------------------------------------------------------------------------
	 * Manage twitter account settings.
	 * -------------------------------------------------------------------------
	 */
	public static function settings()
	{
		/*
		if($_POST)
		{
			Validate::check('username', 'Twitter Username', array('required'));
			Validate::check('password', 'Twitter Password', array('required'));

			if(Validate::passed())
			{
				if(Twitter_Model::update_account(User::get('cid'), $_POST['username'], $_POST['password']))
					Message::set(MSG_OK, 'Twitter settings updated successfully.');
				else
					Message::set(MSG_ERR, 'Error updating Twitter settings. Please try again.');
			}
		}
		*/

		View::load('Social', 'admin/settings', array(
			'twitter' => Twitter_Model::get(User::get('cid'))
		));
	}

}
