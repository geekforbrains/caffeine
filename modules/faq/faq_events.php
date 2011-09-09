<?php
final class FAQ_Events {

    public static function path_callbacks()
    {
        return array(
            // Front
            'faq' => array(
                'title' => 'FAQ',
                'callback' => array('FAQ', 'view'),
                'auth' => true
            ),
            
            // Admin
            'admin/faq' => array(
                'title' => 'FAQ',
                'alias' => 'admin/faq/questions/manage'
            ),

            // Questions & Answers
            'admin/faq/questions' => array(
                'title' => 'Questions &amp; Answers',
                'alias' => 'admin/faq/questions/manage'
            ),
            
            'admin/faq/questions/manage' => array(
                'title' => 'Manage',
                'callback' => array('FAQ_Admin_Questions', 'manage'),
                'auth' => 'manage faq'
            ),
            'admin/faq/questions/create' => array(
                'title' => 'Create',
                'callback' => array('FAQ_Admin_Questions', 'create'),
                'auth' => 'create faq'
            ),
            'admin/faq/questions/edit/%d' => array(
                'title' => 'Edit',
                'callback' => array('FAQ_Admin_Questions', 'edit'),
                'auth' => 'edit faq'
            ),
            'admin/faq/questions/delete/%d' => array(
                'callback' => array('FAQ_Admin_Questions', 'delete'),
                'auth' => 'delete faq'
            ),

            // Categories
            'admin/faq/categories' => array(
                'title' => 'Categories',
                'alias' => 'admin/faq/categories/manage'
            ),
            'admin/faq/categories/manage' => array(
                'title' => 'Manage',
                'callback' => array('FAQ_Admin_Categories', 'manage'),
                'auth' => 'manage faq categories'
            ),
            'admin/faq/categories/create' => array(
                'title' => 'Create',
                'callback' => array('FAQ_Admin_Categories', 'create'),
                'auth' => 'create faq categories'
            ),
            'admin/faq/categories/edit/%d' => array(
                'title' => 'Edit',
                'callback' => array('FAQ_Admin_Categories', 'edit'),
                'auth' => 'edit faq categories'
            ),
            'admin/faq/categories/delete/%d' => array(
                'callback' => array('FAQ_Admin_Categories', 'delete'),
                'auth' => 'delete faq categories'
            )
        );
    }

    public static function database_install()
    {
        return array(
            'faq_categories' => array(
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
                    )
                ),

                'primary key' => array('cid')
            ),

            'faq' => array(
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
                    'question' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    ),
                    'answer' => array(
                        'type' => 'text',
                        'size' => 'big',
                        'not null' => true
                    )
                ),

                'indexes' => array(
                    'category_cid' => array('category_cid')
                ),

                'primary key' => array('cid')
            )
        );
    }

}
