<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Media_Model
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class Media_Model {

	// Clean this shit up!
	public static function get_all($type = null)
	{
		if(is_null($type))
		{
			Database::query('
				SELECT
					mf.*,
					c.created,
					c.updated
				FROM {media_files} mf
					JOIN {content} c ON c.id = mf.cid
				ORDER BY
					c.updated DESC
				'
			);
		}
		else
		{
			Database::query('
				SELECT
					mf.*,
					c.created,
					c.updated
				FROM {media_files} mf
					JOIN {content} c ON c.id = mf.cid
				WHERE
					c.type = %s
				ORDER BY
					c.updated DESC
				',
				$type
			);
		}

		return Database::fetch_all();
	}

	public static function get_file($cid)
	{
		Database::query('
			SELECT
				mf.*,
				c.created,
				c.updated
			FROM {media_files} mf
				JOIN {content} c ON c.id = mf.cid
			WHERE
				mf.cid = %s
			',
			$cid
		);

		if(Database::num_rows() > 0)
			return Database::fetch_array();
		return false;
	}

	public static function create_url($url) 
	{
		$cid = Content::create(MEDIA_TYPE_URL);

		Database::insert('media_url', array(
			'cid' => $cid,
			'url' => $url
		));

		return $cid;
	}

	public static function create_file($data, $media_type = MEDIA_TYPE_FILE) 
	{
		$cid = Content::create($media_type);

		Database::insert('media_files', array(
			'cid' => $cid,
			'name' => $data['name'],
			'hash' => $data['hash'],
			'path' => $data['path'],
			'type' => $data['type'],
			'size' => $data['size']
		));

		return $cid;
	}

}
