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
                if($page = Page::page()->find($slug))
                    return $page->title;
                return null;
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
            'callback' => array('admin_page', 'manage'),
            'permissions' => array('page.manage', 'page.manage_mine')
        ),
        'admin/page/create' => array(
            'title' => 'Create',
            'callback' => array('admin_page', 'create'),
            'permissions' => array('page.create')
        ),
        'admin/page/edit/%d' => array(
            'callback' => array('admin_page', 'edit'),
            'permissions' => array('page.edit', 'page.edit_mine'),
        ),
        'admin/page/delete/%d' => array(
            'callback' => array('admin_page', 'delete'),
            'permissions' => array('page.delete', 'page.delete_mine')
        )
    ),


    'events' => array(
        'user.permission[page.edit_mine]' => function()
        {
            // TODO
        },

        'user.permission[page.delete_mine]' => function() 
        {
            // TODO
        }
    )


);
