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
				WHERE
					c.site_cid = %s
				ORDER BY
					c.updated DESC
				',
				User::current_site()
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
					AND c.site_cid = %s
				ORDER BY
					c.updated DESC
				',
				$type,
				User::current_site()
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
				AND c.site_cid = %s
			',
			$cid,
			User::current_site()
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

	public static function create_file($data, $media_type = MEDIA_TYPE_FILE, $exif = null) 
	{
		$cid = Content::create($media_type);

		Database::insert('media_files', array(
			'cid' => $cid,
			'name' => $data['name'],
			'hash' => $data['hash'],
			'path' => $data['path'],
			'type' => $data['type'],
			'size' => $data['size'],
			'exif' => $exif
		));

		return $cid;
	}

}
