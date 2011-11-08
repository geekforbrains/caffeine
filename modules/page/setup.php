<?php return array(

    'permissions' => array(
        'page.admin_pages' => 'Administer pages',
        'page.manage_pages' => 'Manage all pages',
        'page.manage_my_pages' => 'Manage my pages',
        'page.create' => 'Create pages',
        'page.edit_pages' => 'Edit all pages',
        'page.edit_my_pages' => 'Edit my pages',
        'page.delete_pages' => 'Delete all pages',
        'page.delete_my_pages' => 'Delete my pages'
    ),
    
    'routes' => array(
        // Front
        'page/:slug' => array(
            'title' => function($slug) {
                return 'Some Page';
            },
            'callback' => array('page', 'view'),
            'permissions' => array('page.view_own', 'page.view_all')
        ),

        // Admin
        'admin/page' => array(
            'title' => 'Pages',
            'redirect' => 'admin/page/manage',
            'permissions' => array('page.admin_pages')
        ),
        'admin/page/manage' => array(
            'title' => 'Manage',
            'callback' => array('page_admin', 'manage'),
            'permissions' => array('page.manage_pages', 'page.manage_my_pages')
        ),
        'admin/page/create' => array(
            'title' => 'Create',
            'callback' => array('page_admin', 'create'),
            'permissions' => array('page.create')
        ),
        'admin/page/edit/%d' => array(
            'title' => function($id) {
                return 'Edit Some Title';
            },
            'callback' => array('page_admin', 'edit'),
            'permissions' => array('page.edit_pages', 'page.edit_my_pages')
        ),
        'admin/page/delete/%d' => array(
            'title' => function($id) {
                return 'Delete Some Title';
            },
            'callback' => array('page_admin', 'delete'),
            'permissions' => array('page.delete_pages', 'page.delete_my_pages')
        )
    )

);
