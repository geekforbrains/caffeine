<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Media_Model
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class Media_Model {

	public static function create_url($url) 
	{
		$cid = Content::create(MEDIA_TYPE_URL);

		Database::insert('media_url', array(
			'cid' => $cid,
			'url' => $url
		));

		return $cid;
	}

	public static function create_file($data) 
	{
		$cid = Content::create(MEDIA_TYPE_FILE);

		Database::insert('media_file', array(
			'cid' => $cid,
			'name' => $data['name'],
			'path' => $data['path'],
			'type' => $data['type'],
			'size' => $data['size']
		));

		return $cid;
	}

}
