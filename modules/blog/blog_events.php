<?php
/**
 * =============================================================================
 * Blog_Events
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
final class Blog_Events {

    // TODO
    public static function view_block_paths() 
    {
        return array(
            'Blog' => CAFFEINE_MODULES_PATH . 'blog/blocks/',
            'Blog_Admin' => CAFFEINE_MODULES_PATH . 'blog/blocks/admin/'
        );
    }
    
    // TODO
    public static function path_callbacks()
    {  
        return array(
            // Front
            'blog' => array(
                'title' => 'Blog',
                'callback' => array('Blog', 'posts'),
				'auth' => true,
            ),
            'blog/posts' => array(
                'callback' => array('Blog', 'posts'),
                'visible' => false,
				'auth' => true,
            ),
            'blog/post/%s' => array(
                'callback' => array('Blog', 'post'),
				'auth' => true,
            ),
            'blog/category/%s' => array(
                'callback' => array('Blog', 'posts_by_category'),
				'auth' => true
            ),
            
            // Admin Posts
            'admin/blog' => array(
                'title' => 'Blog',
                'callback' => array('Blog_Admin_Posts', 'manage'),
				'auth' => 'manage blog posts'
            ),
            'admin/blog/posts' => array(
                'title' => 'Posts',
                'callback' => array('Blog_Admin_Posts', 'manage'),
				'auth' => 'manage blog posts',
            ),
            'admin/blog/posts/manage' => array(
                'title' => 'Manage Posts',
                'callback' => array('Blog_Admin_Posts', 'manage'),
				'auth' => 'manage blog posts'
            ),
            'admin/blog/posts/create' => array(
                'title' => 'Create Post',
                'callback' => array('Blog_Admin_Posts', 'create'),
				'auth' => 'create blog posts'
            ),
            'admin/blog/posts/edit/%d' => array(
                'callback' => array('Blog_Admin_Posts', 'edit'),
				'auth' => 'edit blog posts'
            ),
            'admin/blog/posts/delete/%d' => array(
                'callback' => array('Blog_Admin_Posts', 'delete'),
				'auth' => 'delete blog posts'
            ),
            
            // Admin Categories
            'admin/blog/categories' => array(
                'title' => 'Categories',
                'callback' => array('Blog_Admin_Categories', 'manage'),
				'auth' => 'manage blog categories'
            ),
            'admin/blog/categories/manage' => array(
                'title' => 'Manage Categories',
                'callback' => array('Blog_Admin_Categories', 'manage'),
				'auth' => 'manage blog categories'
            ),
            'admin/blog/categories/create' => array(
                'title' => 'Create Category',
                'callback' => array('Blog_Admin_Categories', 'create'),
				'auth' => 'create blog categories'
            ),
            'admin/blog/categories/edit/%d' => array(
                'callback' => array('Blog_Admin_Categories', 'edit'),
				'auth' => 'edit blog categories'
            ),
            'admin/blog/categories/delete/%d' => array(
                'callback' => array('Blog_Admin_Categories', 'delete'),
				'auth' => 'delete blog categories'
            )
        ); 
    }
    
    // TODO
    public static function database_install()
    {
        return array(
            'blog_posts' => array(
                'fields' => array(
                    'cid' => array(
                        'type' => 'int',
						'size' => 'big',
                        'unsigned' => true,
                        'not null' => true
                    ),
                    'title' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    ),
                    'slug' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    ),
                    'content' => array(
                        'type' => 'text',
                        'size' => 'big',
                        'not null' => true
                    )
                ),
                
                'indexes' => array(
                    'slug' => array('slug')
                ),
                
                'primary key' => array('cid')
            ),
            
            'blog_categories' => array(
                'fields' => array(
                    'cid' => array(
                        'type' => 'int',
						'size' => 'big',
                        'unsigned' => true,
                        'not null' => true,
                    ),
                    'name' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    ),
                    'slug' => array(
                        'type' => 'varchar',
                        'length' => 255,
                        'not null' => true
                    )   
                ),
                
                'indexes' => array(
                    'slug' => array('slug')
                ),
                
                'primary key' => array('cid')
            )
        );
    }

}
