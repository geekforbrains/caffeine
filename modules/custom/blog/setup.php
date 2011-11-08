<?php return array(

    // Permissions must be defined here so that they show up in the admin area
    // This way users can select permissions associated with a role
    // The key is the permission looked up in code, the value is the description shown in admin
    'permissions' => array(
        'blog.view_posts' => 'View blog posts',
        'blog.view_user_posts' => 'View users posts'
    ),

    // Routes redirect custom paths to a controller path
    'routes' => array(
        ':num/(.*?)' => 'blog/posts/single/$2-$1',
        'blog/posts' => 'blog/posts/recent'
        /*
        'blog/categories' => 'blog/posts/categories',
        'blog/posts' => 'blog/posts/recent',
        'blog/:slug' => 'blog/posts/single/$1' // Routes redirect custom paths to standard paths
        */
    ),

    'routes' => array(
        'blog' => array(
            'title' => 'Blog',
            'redirect' => 'blog/posts'
        ),
        'blog/posts' => array(
            'title' => 'Recent Posts',
            'callback' => array('posts', 'recent')
        ),
        'blog/categories' => array(
            'title' => 'Categories',
            'callback' => array('posts', 'categories')
        ),
        'blog/category/:slug' => array(
            'title' => function($slug) {
                $name = Blog::category()->find($slug)->name;
                return sprintf('Posts found in "%s"', $name);
            },
            'callback' => array('posts', 'postsByCatgory')
        ),
        'blog/:slug' => array(
            'title' => function($slug) {
                return Blog::post()->find($slug)->title;
            },
            'callback' => array('posts', 'single')
        )
    )

);
