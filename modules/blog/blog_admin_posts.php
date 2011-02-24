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
		$published = Blog_Model_Posts::get_all(1);
		$drafts = Blog_Model_Posts::get_all(0);

        View::load('Blog_Admin', 'blog_admin_posts_manage',
            array(
				'published' => $published,
				'drafts' => $drafts
			)
		);
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
			Validate::check('category_cid', 'Category', array('required'));
			Validate::check('title', 'Title', array('required'));

			if(Validate::passed())
			{
				$published = isset($_POST['publish']) ? 1 : 0;

				$cid = Blog_Model_Posts::create(
					$_POST['title'], 
					$_POST['content'],
					String::tagify($_POST['title']),
					$published
				);

				if($cid)
				{
					Blog_Model_Posts::add_to_category($cid, $_POST['category_cid']);
				
					if($published)
						Message::store(MSG_OK, 'Post successfully published.');
					else
						Message::store(MSG_OK, 'Post successfully saved to drafts.');

					Router::redirect('admin/blog/posts/manage');
				}
				else
					Message::set(MSG_ERR, 'Unkown error creating post. Please try again.');
			}
        }
        
        View::load('Blog_Admin', 'blog_admin_posts_create',
            array('categories' => Blog_Model_Categories::get_all()));
    }
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function edit($cid) 
    {
		if(!Blog_Model_Posts::get_by_cid($cid))
			Router::redirect('admin/blog/posts');

        if($_POST)
        {
			if(isset($_POST['delete']))
			{
				self::delete($cid);
				return;
			}	

			Validate::check('category_cid', 'Category', array('required'));
			Validate::check('title', 'Title', array('required'));
			Validate::check('slug', 'Slug', array('required'));

			if(Validate::passed())
			{
				$published = isset($_POST['published']) ? 1 : 0;

				Blog_Model_Posts::update(
					$cid, 
					$_POST['title'], 
					$_POST['content'],
					$_POST['slug'],
					$published
				);

				Blog_Model_Posts::update_categories($cid, $_POST['category_cid']);

				Message::store(MSG_OK, 'Post updated successfully.');
				Router::redirect('admin/blog/posts/manage');
			}
        }
        
        View::load('Blog_Admin', 'blog_admin_posts_edit',
            array(
				'post' => Blog_Model_Posts::get_by_cid($cid),
				'categories' => Blog_Model_Categories::get_all()
			)
		);
    }
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function delete($cid) 
    {
        if(Blog_Model_Posts::delete($cid))
			Message::store(MSG_OK, 'Post deleted successfully.');
		else
			Message::store(MSG_ERR, 'Unkown error while deleting post. Please try again.');

        Router::redirect('admin/blog/posts/manage');
    }

}
