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
        View::load('Blog', 'posts', 
            array('posts' => Blog_Model_Posts::get_all($published, $limit)));
    }
    
    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
    public static function posts_by_category($slug = null)
    {
		$category = Blog_Model_Categories::get_by_slug($slug);
        View::load('Blog', 'posts',
            array(
                'category' => $category,
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

		$post = Blog_Model_Posts::get_by_slug($slug);
        View::load('Blog', 'post', array('post' => $post));
    }

	/**
	 * -------------------------------------------------------------------------
	 * Displays the latest blog post.
	 * -------------------------------------------------------------------------
	 */
	public static function latest_post()
	{
		View::load('Blog', 'latest_post', 
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
            
        View::load('Blog', 'post_comments', 
            array('comments' => Blog_Model_Posts::get_comments($slug)));
    }
    
    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
    public static function categories()
    {
        View::load('Blog', 'categories', 
            array('categories' => Blog_Model_Categories::get_all()));
    }

    /**
     * -------------------------------------------------------------------------
     * DEPRECATED!!!
     * -------------------------------------------------------------------------
     */
    public static function archive()
    {
        View::load('Blog', 'archive',
            array('posts' => Blog_Model_Posts::get_all(1, 100)));
    }

	public static function category_title($slug)
	{
		$category = Blog_Model_Categories::get_by_slug($slug);
		if($category)
			return sprintf('Blog Posts in the "%s" Category', $category['name']);
	}

	public static function post_title($slug)
	{
		$post = Blog_Model_Posts::get_by_slug($slug);
		if($post)
			return $post['title'];
	}

    public static function rss()
    {
        header("Content-Type: application/rss+xml; charset=ISO-8859-1");

        $rssfeed = '<?xml version="1.0" encoding="ISO-8859-1"?>';
        $rssfeed .= '<rss version="2.0">';
        $rssfeed .= '<channel>';
        $rssfeed .= '<title>Blog RSS feed</title>';
        $rssfeed .= '<link>' . Router::full_url('/') . '</link>';
        $rssfeed .= '<description>RSS feed of recent blog posts</description>';
        $rssfeed .= '<language>en-us</language>';
        $rssfeed .= '<copyright>Copyright (C) ' . date('Y') . ' ' . Router::base() . '</copyright>';

        $posts = Blog_Model_Posts::get_all(1, BLOG_RSS_LIMIT);

        foreach($posts as $p)
        {
            $rssfeed .= '<item>';
            $rssfeed .= '<title>' . $p['title'] . '</title>';
            $rssfeed .= '<description>' . substr(strip_tags($p['content']), 0, 255) . '...</description>';
            $rssfeed .= '<link>' . Router::full_url('blog/post/%s', $p['slug']) . '</link>';
            $rssfeed .= '<pubDate>' . date("D, d M Y H:i:s O", $p['updated']) . '</pubDate>';
            $rssfeed .= '</item>';
        }
 
        $rssfeed .= '</channel>';
        $rssfeed .= '</rss>';

        die($rssfeed);
    }

}
