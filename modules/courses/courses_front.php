<?php

class Courses_Front {

    public static function courses()
    {
        $categories = Courses_Model_Categories::get_all();
        $courses = Courses_Model::get_all();

        $sorted = array();
        foreach($categories as $c)
        {
            $sorted[$c['cid']] = array(
                'name' => $c['name'],
                'courses' => array()
            );
        }

        foreach($courses as $c)
            $sorted[$c['category_cid']]['courses'][] = $c;

        View::load('Courses', 'courses', array(
            'categories' => $categories,
            'courses' => $courses,
            'courses_by_category' => $sorted
        ));
    }

    public static function details($cid)
    {
        if(!$course = Courses_Model::get_by_cid($cid))
            return false;

        View::load('Courses', 'details', array(
            'course' => $course
        ));
    }

}
