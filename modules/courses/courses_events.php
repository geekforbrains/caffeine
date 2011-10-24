<?php

class Courses_Events {

    public static function path_callbacks()
    {
        return array(
            // Front
            'courses' => array(
                'title' => 'Courses',
                'callback' => array('Courses', 'all'),
                'auth' => true
            ),
            'courses/%d' => array(
                'title' => 'Course',
                'callback' => array('Courses', 'details'),
                'auth' => true
            ),

            // Admin
            'admin/courses' => array(
                'title' => 'Courses',
                'alias' => 'admin/courses/courses/manage'
            ),

            // Admin Courses
            'admin/courses/courses' => array(
                'title' => 'Courses',
                'alias' => 'admin/courses/courses/manage'
            ),
            'admin/courses/courses/manage' => array(
                'title' => 'Manage',
                'callback' => array('Courses_Admin', 'manage'),
                'auth' => 'manage courses'
            ),
            'admin/courses/courses/create' => array(
                'title' => 'Create',
                'callback' => array('Courses_Admin', 'create'),
                'auth' => 'create courses'
            ),
            'admin/courses/courses/edit/%d' => array(
                'callback' => array('Courses_Admin', 'edit'),
                'auth' => 'edit courses'
            ),
            'admin/courses/courses/edit/%d/delete-photo/%d' => array(
                'callback' => array('Courses_Admin', 'delete_photo'),
                'auth' => 'delete course photos'
            ),
            'admin/courses/courses/delete/%d' => array(
                'callback' => array('Courses_Admin', 'delete'),
                'auth' => 'delete courses'
            ),

            // Admin Categories
            'admin/courses/categories' => array(
                'title' => 'Categories',
                'alias' => 'admin/courses/categories/manage'
            ),
            'admin/courses/categories/manage' => array(
                'title' => 'Manage',
                'callback' => array('Courses_Admin_Categories', 'manage'),
                'auth' => 'manage course categories'
            ),
            'admin/courses/categories/create' => array(
                'title' => 'Create',
                'callback' => array('Courses_Admin_Categories', 'create'),
                'auth' => 'create course categories'
            ),
            'admin/courses/categories/edit/%d' => array(
                'callback' => array('Courses_Admin_Categories', 'edit'),
                'auth' => 'edit course categories'
            ),
            'admin/courses/categories/delete/%d' => array(
                'callback' => array('Courses_Admin_Categories', 'delete'),
                'auth' => 'delete course categories'
            )
        );
    }

    public static function database_install()
    {
        return array(
            'course_categories' => array(
                'fields' => array(
                    'cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'name' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    ),
                    'short_desc' => array(
                        'type' => 'text',
                        'size' => 'normal',
                        'not null' => true
                    ),
                    'long_desc' => array(
                        'type' => 'text',
                        'size' => 'normal',
                        'not null' => true
                    )
                ),

                'primary key' => array('cid')
            ),

            'courses' => array(
                'fields' => array(
                    'cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'category_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'name' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    ),
                    'short_desc' => array(
                        'type' => 'text',
                        'size' => 'normal',
                        'not null' => true
                    ),
                    'long_desc' => array(
                        'type' => 'text',
                        'size' => 'normal',
                        'not null' => true
                    ),
                    'what_to_bring' => array(
                        'type' => 'text',
                        'size' => 'normal',
                        'not null' => true
                    ),
                    'length' => array( // measured in days
                        'type' => 'int',
                        'size' => 'normal',
                        'not null' => true
                    ),
                    'start_date' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'end_date' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'price' => array(
                        'type' => 'double',
                        'size' => 'normal',
                        'not null' => true
                    )
                ),

                'indexes' => array(
                    'category_cid' => array('category_cid'),
                    'start_date' => array('start_date'),
                    'end_date' => array('end_date'),
                    'price' => array('price')
                ),

                'primary key' => array('cid')
            ),

            'course_photos' => array(
                'fields' => array(
                    'course_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'media_cid' => array(
                        'type' => 'int',
                        'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    )
                ),

                'indexes' => array(
                    'course_cid' => array('course_cid'),
                    'media_cid' => array('media_cid')
                )
            )
        );
    }

}
