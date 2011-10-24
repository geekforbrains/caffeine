<?php

class Courses_Model_Categories {

    public static function get_all()
    {
        Database::query('SELECT * FROM {course_categories} ORDER BY name ASC');
        return Database::fetch_all();
    }

    public static function get_by_cid($cid)
    {
        Database::query('SELECT * FROM {course_categories} WHERE cid = %s', $cid);
       
        if(Database::num_rows() > 0)
            return Database::fetch_array();
        return false;
    }

    public static function create($data)
    {
        $cid = Content::create(COURSES_TYPE_CATEGORY);
        $status = Database::insert('course_categories', array(
            'cid' => $cid,
            'name' => $data['name'],
            'short_desc' => $data['short_desc'],
            'long_desc' => $data['long_desc']
        ));

        if($status)
            return $cid;
        return false;
    }

    public static function update($cid, $data)
    {
        Content::update($cid);
        return Database::update('course_categories',
            array(
                'short_desc' => $data['short_desc'],
                'long_desc' => $data['long_desc']
            ),
            array('cid' => $cid)
        );
    }

    public static function delete($cid)
    {
        Content::delete($cid);
        return Database::delete('course_categories', array('cid' => $cid));
    }

}
