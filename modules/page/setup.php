<?php return array(

    'permissions' => array(
        'page.admin' => 'Administer pages',
        'page.manage' => 'Manage all pages',
        'page.manage_mine' => 'Manage my pages',
        'page.create' => 'Create pages',
        'page.edit' => 'Edit all pages',
        'page.edit_mine' => 'Edit my pages',
        'page.delete' => 'Delete all pages',
        'page.delete_mine' => 'Delete my pages'
    ),
    
    'routes' => array(
        // Front
        'page/:slug' => array(
            'title' => function($slug) {
                return 'Some Page';
            },
            'callback' => array('page', 'view')
        ),

        // Admin
        'admin/page' => array(
            'title' => 'Pages',
            'redirect' => 'admin/page/manage',
            'permissions' => array('page.admin')
        ),
        'admin/page/manage' => array(
            'title' => 'Manage',
            'callback' => array('page_admin', 'manage'),
            'permissions' => array('page.manage', 'page.manage_mine')
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
            'permissions' => array('page.edit', 'page.edit_mine')
        ),
        'admin/page/delete/%d' => array(
            'title' => function($id) {
                return 'Delete Some Title';
            },
            'callback' => array('page_admin', 'delete'),
            'permissions' => array('page.delete', 'page.delete_mine')
        )
    ),

    /**
     * Calls event when accessing a page that required the "page.delete_mine" permission. This allows events
     * to determine the functionality of a permission check.
     */
    'events' => array(
        'user.permission[page.delete_mine]' => function() 
        {

        }
    )

);
