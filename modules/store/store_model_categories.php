<?php
class Store_Model_Categories {

    public static function get_all()
    {
        Database::query('SELECT * FROM {store_categories}');
        return Database::fetch_all();
    }

    public static function get_by_cid($cid)
    {
        Database::query('SELECT * FROM {store_categories} WHERE cid = %s', $cid);

        if(Database::num_rows() > 0)
            return Database::fetch_array();
        return false;
    }

    public static function get_by_slug($slug)
    {
        Database::query('SELECT * FROM {store_categories} WHERE slug LIKE %s', $slug);
        
        if(Database::num_rows() > 0)
            return Database::fetch_array();
        return false;
    }

    public static function get_by_parent_cid($parent_cid)
    {
        Database::query('SELECT * FROM {store_categories} WHERE parent_cid = %s ORDER BY name ASC', $parent_cid);
        return Database::fetch_all();
    }

    public static function get_first_subcategory($cid)
    {
        Database::query('SELECT * FROM {store_categories} WHERE parent_cid = %s LIMIT 1', $cid);
        if(Database::num_rows() > 0)
            return Database::fetch_array();
        return false;
    }
    
    public static function create($parent_cid, $name, $slug)
    {
        $cid = Content::create(STORE_TYPE_CATEGORY);
        return Database::insert('store_categories', array(
            'cid' => $cid,
            'parent_cid' => $parent_cid,
            'name' => $name,
            'slug' => $slug
        ));
    }

    public static function update($cid, $parent_cid, $name, $slug)
    {
        Content::update($cid);
        return Database::update('store_categories', 
            array(
                'parent_cid' => $parent_cid,
                'name' => $name,
                'slug' => $slug
            ),
            array('cid' => $cid)
        );
    }

    public static function delete($cid)
    {
        Content::delete($cid);
        return Database::delete('store_categories', array('cid' => $cid));
    }

}
