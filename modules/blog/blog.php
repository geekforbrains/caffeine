<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Blog
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class Blog {
    
    /**
     * -------------------------------------------------------------------------
     * Displays a list of blog posts by page.
     * -------------------------------------------------------------------------
     */
    public static function posts() 
    {
        View::load('Blog', 'blog_posts', 
            array('posts' => Blog_Posts::get_all()));
    }
    
    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
    public static function posts_by_category($slug = null)
    {
        View::load('Blog', 'blog_posts',
            array(
                'category' => Blog_Categories::get_by_slug($slug),
                'posts' => Blog_Posts::get_all_by_category_slug($slug)
            )
        );
    }
    
    /**
     * -------------------------------------------------------------------------
     * Displays a blog post based on its "slug".
     * -------------------------------------------------------------------------
     */
    public static function post($slug = null) 
    {
        if(is_null($slug))
            Router::redirect('blog');
          
        View::load('Blog', 'blog_post',
            array('post' => Blog_Posts::get_by_slug($slug)));
    }
    
    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
    public static function post_comments() 
    {
        $slug = Router::segment(2);
        if(!$slug)
            Router::redirect('blog');
            
        View::load('Blog', 'blog_post_comments', 
            array('comments' => Blog_Posts::get_comments($slug)));
    }
    
    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
    public static function categories()
    {
        View::load('Blog', 'blog_categories', 
            array('categories' => Blog_Categories::get_all()));
    }

}
