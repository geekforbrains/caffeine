<?php
final class Social_Events {

	public static function path_callbacks()
	{
		return array(
			'admin/social' => array(
				'title' => 'Social',
				'alias' => 'admin/social/post'
			),
			'admin/social/post' => array(
				'title' => 'Post',
				'callback' => array('Social_Admin', 'post'),
				'auth' => 'social posts'
			),
			'admin/social/settings' => array(
				'title' => 'Settings',
				'callback' => array('Social_Admin', 'settings'),
				'auth' => 'manage settings'
			)
		);
	}

}
