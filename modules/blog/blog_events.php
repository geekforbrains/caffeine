<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Blog_Events
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
final class Blog_Events {

	/**
	 * -------------------------------------------------------------------------
	 * Implements the Path::callbacks event.
	 * -------------------------------------------------------------------------
	 */
    public static function path_callbacks()
    {  
        return array(
            // Front
            'blog' => array(
                'title' => 'Blog',
				'alias' => 'blog/posts'
            ),
            'blog/posts' => array(
				'title' => 'Blog',
                'callback' => array('Blog', 'posts'),
                'visible' => false,
				'auth' => true,
            ),
            'blog/category/%s' => array(
                'callback' => array('Blog', 'posts_by_category'),
				'auth' => true
            ),
			'blog/archive' => array(
				'callback' => array('Blog', 'archive'),
				'auth' => true
			),
            'blog/%s' => array(
                'callback' => array('Blog', 'post'),
				'auth' => true,
            ),
            
            // Admin Posts
            'admin/blog' => array(
				'title' => 'Blog',
				'alias' => 'admin/blog/posts/manage'
            ),
            'admin/blog/posts' => array(
				'title' => 'Posts',
				'alias' => 'admin/blog/posts/manage'
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
				'title' => 'Edit Post',
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
				'alias' => 'admin/blog/categories/manage'
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
				'title' => 'Edit Category',
                'callback' => array('Blog_Admin_Categories', 'edit'),
				'auth' => 'edit blog categories'
            ),
            'admin/blog/categories/delete/%d' => array(
                'callback' => array('Blog_Admin_Categories', 'delete'),
				'auth' => 'delete blog categories'
            )
        ); 
    }
    
	/**
	 * -------------------------------------------------------------------------
	 * Implements the Database::install event.
	 * -------------------------------------------------------------------------
	 */
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
                    ),
					'published' => array(
						'type' => 'int',
						'size' => 'tiny',
						'unsigned' => true,
						'not null' => true
					)
                ),
                
                'indexes' => array(
                    'slug' => array('slug'),
					'published' => array('published')
                ),
                
                'primary key' => array('cid')
            ),

			'blog_post_categories' => array(
				'fields' => array(
					'post_cid' => array(
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
					)
				),

				'indexes' => array(
					'post_cid' => array('post_cid'),
					'category_cid' => array('category_cid')
				)
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
