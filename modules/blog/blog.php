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
	 *
	 * @param $published
	 *		Defines whether to get published or un-published posts. "NULL" will
	 *		return both. Defaults to published.
	 *
	 * @param $limit
	 *		Limit the amount of posts returned per page. Default is set in 
	 *		blog_config.php.
     * -------------------------------------------------------------------------
     */
    public static function posts($published = 1, $limit = BLOG_POSTS_LIMIT) 
    {
        View::load('Blog', 'blog_posts', 
            array('posts' => Blog_Model_Posts::get_all($published, $limit)));
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
                'category' => Blog_Model_Categories::get_by_slug($slug),
                'posts' => Blog_Model_Posts::get_all_by_category_slug($slug)
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
            array('post' => Blog_Model_Posts::get_by_slug($slug)));
    }

	/**
	 * -------------------------------------------------------------------------
	 * Displays the latest blog post.
	 * -------------------------------------------------------------------------
	 */
	public static function latest_post()
	{
		View::load('Blog', 'blog_latest_post', 
			array('post' => Blog_Model_Posts::get_latest()));
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
            array('comments' => Blog_Model_Posts::get_comments($slug)));
    }
    
    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
    public static function categories()
    {
        View::load('Blog', 'blog_categories', 
            array('categories' => Blog_Model_Categories::get_all()));
    }

}
