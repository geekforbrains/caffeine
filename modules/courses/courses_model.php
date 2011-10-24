<?php

class Courses_Model {

    public static function get_all()
    {
        Database::query('
            SELECT 
                c.*,
                cc.name AS category
            FROM {courses} c
                JOIN {course_categories} cc ON cc.cid = c.category_cid
            ORDER BY
                start_date, name ASC
            '
        );

        return Database::fetch_all();
    }

    public static function get_by_cid($cid)
    {
        Database::query('SELECT * FROM {courses} WHERE cid = %s', $cid);

        if(Database::num_rows() > 0)
            return Database::fetch_array();
        return false;
    }

    public static function create($data)
    {
        $cid = Content::create(COURSES_TYPE_COURSE);
        $status = Database::insert('courses', array(
            'cid' => $cid,
            'category_cid' => $data['category_cid'],
            'name' => $data['name'],
            'short_desc' => $data['short_desc'],
            'long_desc' => $data['long_desc'],
            'what_to_bring' => $data['what_to_bring'],
            'length' => $data['length'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'price' => $data['price']
        ));

        if($status)
            return $cid;
        return false;
    }

    public static function update($cid, $data)
    {
        Content::update($cid);

        return Database::update('courses', 
            array(
                'category_cid' => $data['category_cid'],
                'name' => $data['name'],
                'short_desc' => $data['short_desc'],
                'long_desc' => $data['long_desc'],
                'what_to_bring' => $data['what_to_bring'],
                'length' => $data['length'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'price' => $data['price']
            ),
            array('cid' => $cid)
        );
    }

    public static function delete($cid)
    {
        // First delete photos
        $photos = self::get_photos($cid);
        
        foreach($photos as $photo)
            self::delete_photo($cid, $photo['media_cid']);

        // Then delete course
        Content::delete($cid);
        return Database::delete('courses', array('cid' => $cid));
    }

    /**
     * ========================================================
     * START PHOTO METHODS
     * ========================================================
     */

    public static function get_photos($course_cid)
    {
        Database::query('SELECT * FROM {course_photos} WHERE course_cid = %s', $course_cid);
        return Database::fetch_all();
    }

    public static function add_photo($course_cid, $media_cid)
    {
        return Database::insert('course_photos', array(
            'course_cid' => $course_cid,
            'media_cid' => $media_cid
        ));
    }

    public static function delete_photo($course_cid, $media_cid)
    {
        Media::delete($media_cid);
        return Database::delete('course_photos', array('course_cid' => $course_cid, 'media_cid' => $media_cid));
    }

}
