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

        /**
         * Registers the "page" module as a support multilanguage module
         */
        'multilanguage.modules' => function() {
            return 'page';
        },

        /**
         * Returns an array of content that the page module provides.
         */
        'multilanguage.content[page]' => function()
        {
            $content = array('page' => array());
            $pages = Page::page()->all(); 

            if($pages)
                foreach($pages as $page)
                    $content['page'][$page->id] = $page->title;

            return $content;
        },

        /**
         * Returns an array of content details for a page
         */
        'multilanguage.content_type[page][page]' => function()
        {
            return array(
                'title' => 'text',
                'body' => 'textarea'
            );
        }
    )

);
