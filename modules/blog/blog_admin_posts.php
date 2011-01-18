<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Blog_Admin_Posts
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class Blog_Admin_Posts {

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function manage() 
    {
        View::load('Blog_Admin', 'blog_admin_posts_manage',
            array('posts' => Blog_Posts::get_all()));
    }
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function create() 
    {
        if($_POST)
        {
			$user = User::get_current();
			$published = isset($_POST['publish']) ? 1 : 0;

            Blog_Posts::create(
                $_POST['title'], 
                $_POST['content'],
                String::tagify($_POST['title']),
				$_POST['categories'],
				$published
            );
            
            Message::store('notify', 'Post created successfully.');
            Router::redirect('admin/blog/posts/manage');
        }
        
        View::load('Blog_Admin', 'blog_admin_posts_create',
            array('categories' => Blog_Categories::get_all()));
    }
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function edit($cid) 
    {
        if($_POST)
        {
			if(isset($_POST['delete']))
			{
				self::delete($cid);
				return;
			}	

			$published = isset($_POST['publish']) ? 1 : 0;

            Blog_Posts::update(
				$cid, 
				$_POST['title'], 
				$_POST['content'],
                String::tagify($_POST['title']),
				$published
			);
            
            Message::store('notify', 'Post updated successfully.');
            Router::redirect('admin/blog/posts/manage');
        }
        
        View::load('Blog_Admin', 'blog_admin_posts_edit',
            array('post' => Blog_Posts::get_by_cid($cid)));
    }
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function delete($cid) 
    {
        Blog_Posts::delete($cid);
        
        Message::store('notify', 'Post deleted successfully.');
        Router::redirect('admin/blog/posts/manage');
    }

}
