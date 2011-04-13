<?php
final class Twitter_Events {

	public static function path_callbacks()
	{
		return array(
			'twitter/activate' => array(
				'title' => 'Activate Twitter',
				'callback' => array('Twitter', 'activate'),
				'auth' => 'activate twitter',
				'visible' => false
			),
			'twitter/disable' => array(
				'title' => 'Disable Twitter',
				'callback' => array('Twitter', 'disable'),
				'auth' => 'disable twitter',
				'visible' => false
			),
			'twitter/callback/%d' => array(
				'title' => 'Activate Twitter',
				'callback' => array('Twitter', 'callback'),
				'auth' => 'activate twitter',
				'visible' => false
			)
		);
	}

	public static function database_install()
	{
		return array(
			'twitter_accounts' => array(
				'fields' => array(
					'user_cid' => array(
						'type' => 'int',
						'size' => 'big',
						'unsigned' => true,
						'not null' => true
					),
					'token' => array(
						'type' => 'text',
						'size' => 'normal',
						'not null' => true
					)
				),

				'indexes' => array(
					'user_cid' => array('user_cid')
				)
			)
		);
	}

}
