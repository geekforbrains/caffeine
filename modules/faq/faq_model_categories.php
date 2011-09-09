<?php

class FAQ_Model_Categories {

    public static function get_all()
    {
        Database::query('SELECT * FROM {faq_categories}');
        return Database::fetch_all();
    }

    public static function get_by_cid($cid)
    {
        Database::query('SELECT * FROM {faq_categories} WHERE cid = %s', $cid);

        if(Database::num_rows() > 0)
            return Database::fetch_array();
        return false;
    }

    public static function create($name)
    {
        $cid = Content::create(FAQ_TYPE_CATEGORY);
        $status = Database::insert('faq_categories', array(
            'cid' => $cid,
            'name' => $name
        ));

        if($status)
            return $cid;
        return false;
    }

    public static function update($cid, $name)
    {
        Content::update($cid);
        return Database::update('faq_categories', 
            array('name' => $name),
            array('cid' => $cid)
        );
    }

    public static function delete($cid)
    {
        Content::delete($cid);
        return Database::delete('faq_categories', array('cid' => $cid));
    }

}
