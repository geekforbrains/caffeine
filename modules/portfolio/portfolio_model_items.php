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
                pc.name, pi.name ASC
        ');

        return Database::fetch_all();
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

            return $row;
        }

        return false;
    }

    public static function get_by_category_slug($category_slug)
    {
        Database::query('
            SELECT
                
    }

    public static function get_by_category_cid($category_cid)
    {

    }

    public static function create($category_cid, $name, $desc)
    {
        $cid = Content::create(PORTFOLIO_TYPE_ITEM);
        $status = Database::insert('portfolio_items', array(
            'cid' => $cid,
            'category_cid' => $category_cid,
            'name' => $name,
            'description' => $desc
        ));

        if($status)
            return $cid;
        return false;
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
                mf.*
            FROM {portfolio_item_photos} pip
                LEFT JOIN {media_files} mf ON mf.cid = pip.media_cid
            WHERE
                pip.item_cid = %s
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
