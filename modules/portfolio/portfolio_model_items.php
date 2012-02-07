<?php

class Portfolio_Model_Items {

    public static function get_all()
    {
        Database::query('
            SELECT
                pi.*,
                pc.name AS category
            FROM {portfolio_items} pi
                LEFT JOIN {portfolio_categories} pc ON pc.cid = pi.category_cid
            ORDER BY
                pi.weight ASC
        ');

        if(Database::num_rows() > 0)
        {
            $rows = Database::fetch_all();

            foreach($rows as &$row)
            {
                $data = self::get_data_by_cid($row['cid']);

                foreach($data as $d)
                    $row[$d['name']] = $d['value'];

                $row['photos'] = self::get_photos_by_cid($row['cid']);
                $row['videos'] = self::get_videos_by_cid($row['cid']);
            }

            return $rows;
        }

        return array();
    }

    public static function get_by_cid($cid)
    {
        Database::query('SELECT * FROM {portfolio_items} WHERE cid = %s', $cid);
        
        if(Database::num_rows() > 0)
        {
            $row = Database::fetch_array();
            $data = self::get_data_by_cid($cid);

            foreach($data as $d)
                $row[$d['name']] = $d['value'];

            $row['photos'] = self::get_photos_by_cid($cid);
            $row['videos'] = self::get_videos_by_cid($cid);

            return $row;
        }

        return false;
    }

    public static function get_by_slug($slug)
    {
        Database::query('SELECT * FROM {portfolio_items} WHERE slug = %s', $slug);
        
        if(Database::num_rows() > 0)
        {
            $row = Database::fetch_array();
            $data = self::get_data_by_cid($row['cid']);

            foreach($data as $d)
                $row[$d['name']] = $d['value'];

            $row['photos'] = self::get_photos_by_cid($row['cid']);
            $row['videos'] = self::get_videos_by_cid($row['cid']);

            return $row;
        }

        return false;
    }

    public static function get_by_category_slug($category_slug)
    {
        Database::query('
            SELECT
                pi.*
            FROM {portfolio_items} pi
                JOIN {portfolio_categories} pc ON pc.cid = pi.category_cid
            WHERE
                pc.slug = %s
            ',
            $category_slug
        );

        if(Database::num_rows() > 0)
        {
            $rows = Database::fetch_all();

            foreach($rows as &$row)
            {
                $data = self::get_data_by_cid($row['cid']);

                foreach($data as $d)
                    $row[$d['name']] = $d['value'];

                $row['photos'] = self::get_photos_by_cid($row['cid']);
                $row['videos'] = self::get_videos_by_cid($row['cid']);
            }

            return $rows;
        }

        return array();
    }

    public static function get_by_category_cid($category_cid)
    {
        Database::query('SELECT * FROM {portfolio_items} WHERE category_cid = %s ORDER BY weight ASC', $category_cid);

        if(Database::num_rows() > 0)
        {
            $rows = Database::fetch_all();

            foreach($rows as &$row)
            {
                $data = self::get_data_by_cid($row['cid']);

                foreach($data as $d)
                    $row[$d['name']] = $d['value'];

                $row['photos'] = self::get_photos_by_cid($row['cid']);
                $row['videos'] = self::get_videos_by_cid($row['cid']);
            }

            return $rows;
        }

        return array();
    }


    public static function create($category_cid, $name, $desc, $weight, $slug, $thumb_cid)
    {
        $cid = Content::create(PORTFOLIO_TYPE_ITEM);
        $status = Database::insert('portfolio_items', array(
            'cid' => $cid,
            'category_cid' => $category_cid,
            'thumb_cid' => $thumb_cid,
            'slug' => $slug,
            'name' => $name,
            'description' => $desc,
				'weight' => $weight
        ));

        if($status)
            return $cid;
        return false;
    }

    public static function update($cid, $category_cid, $name, $desc, $weight, $slug, $thumb_cid = 0)
    {
        $update = array(
            'category_cid' => $category_cid,
            'slug' => $slug,
            'name' => $name,
            'description' => $desc,
				'weight' => $weight
        );

        if($thumb_cid > 0)
            $update['thumb_cid'] = $thumb_cid;

        return Database::update('portfolio_items', $update, array('cid' => $cid));
    }

    public static function get_data_by_cid($item_cid)
    {
        Database::query('SELECT * FROM {portfolio_item_data} WHERE item_cid = %s', $item_cid);
        return Database::fetch_all();
    }

    // Does insert AND update
    public static function add_data($item_cid, $name, $value)
    {
        Database::query('SELECT * FROM {portfolio_item_data} WHERE item_cid = %s AND name = %s', $item_cid, $name);

        // Update
        if(Database::num_rows() > 0)
        {
            Database::update('portfolio_item_data',
                array('value' => $value),
                array(
                    'item_cid' => $item_cid,
                    'name' => $name
                )
            );

            return true;
        }

        // Create
        else
        {
            return Database::insert('portfolio_item_data', array(
                'item_cid' => $item_cid,
                'name' => $name,
                'value' => $value
            ));
        }
    }

    public static function get_data($item_cid, $name)
    {
        Database::query('SELECT value FROM {portfolio_item_data} WHERE item_cid = %s AND name = %s', $item_cid, $name);

        if(Database::num_rows() > 0)
            return Database::fetch_single('value');
        return false;
    }

    public static function delete($cid)
    {
        Content::delete($cid);
        Database::delete('portfolio_item_data', array('item_cid' => $cid));
        return Database::delete('portfolio_items', array('cid' => $cid));
    }

    public static function get_photos_by_cid($cid)
    {
        Database::query('
            SELECT
                *
            FROM {portfolio_item_photos} pip
                LEFT JOIN {media_files} mf ON mf.cid = pip.media_cid
            WHERE
                pip.item_cid = %s
			ORDER BY
				weight
            ',
            $cid
        );

        return Database::fetch_all();
    }

    public static function add_photo($cid, $media_cid)
    {
        return Database::insert('portfolio_item_photos', array(
            'item_cid' => $cid,
            'media_cid' => $media_cid
        ));
    }

	public static function update_photos_order($cid, $weights)
	{
		$photos = self::get_photos_by_cid($cid);

		foreach($photos as $photo)
		{
			$weight = array_shift($weights);
			Database::update('portfolio_item_photos', 
				array('weight' => $weight),
				array('media_cid' => $photo['media_cid'])
			);
		}
	}

    public static function delete_photo($item_cid, $media_cid)
    {
        Media::delete($media_cid);
        return Database::delete('portfolio_item_photos', array('item_cid' => $item_cid, 'media_cid' => $media_cid));
    }

    public static function get_videos_by_cid($cid)
    {
        Database::query('
            SELECT
                v.*
            FROM {portfolio_item_videos} piv
                LEFT JOIN {videos} v ON v.cid = piv.video_cid
            WHERE
                piv.item_cid = %s
            ',
            $cid
        );

        return Database::fetch_all();
    }

    public static function add_video($item_cid, $video_cid)
    {
        Database::insert('portfolio_item_videos', array(
            'item_cid' => $item_cid,
            'video_cid' => $video_cid
        ));
    }

    public static function delete_video($item_cid, $video_cid)
    {
        Video_Model::delete($video_cid);
        return Database::delete('portfolio_item_videos', array('item_cid' => $item_cid, 'video_cid' => $video_cid));
    }

}
