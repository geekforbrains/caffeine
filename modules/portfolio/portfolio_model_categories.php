<?php

class Portfolio_Model_Categories {

    public static function get_all()
    {
        Database::query('SELECT * FROM {portfolio_categories} ORDER BY name ASC');
        return Database::fetch_all();
    }

    public static function get_by_cid($cid)
    {
        Database::query('SELECT * FROM {portfolio_categories} WHERE cid = %s', $cid);

        if(Database::num_rows() > 0)
            return Database::fetch_array();
        return false;
    }

    public static function get_by_slug($slug)
    {
        Database::query('SELECT * FROM {portfolio_categories} WHERE slug = %s', $slug);

        if(Database::num_rows() > 0)
            return Database::fetch_array();
        return false;
    }

    public static function create($name)
    {
        $cid = Content::create(PORTFOLIO_TYPE_CATEGORY);
        $status = Database::insert('portfolio_categories', array(
            'cid' => $cid,
            'slug' => String::tagify($name),
            'name' => $name
        ));

        if($status)
            return $cid;
        return false;
    }

    public static function update($cid, $name)
    {
        Content::update($cid);
        return Database::update('portfolio_categories',
            array(
                'slug' => String::tagify($name),
                'name' => $name
            ),
            array('cid' => $cid)
        );
    }

    public static function delete($cid)
    {
        Content::delete($cid);
        return Database::delete('portfolio_categories', array('cid' => $cid));
    }

}
