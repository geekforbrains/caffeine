<?php
/**
 * =============================================================================
 * Blog_Admin_Posts
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class Blog_Admin_Posts {

    public static function manage() 
    {
        View::load('Blog_Admin', 'blog_admin_posts_manage',
            array('posts' => Blog_Posts::get_all()));
    }
    
    public static function create() 
    {
        if($_POST)
        {
			$user = User::get_current();

            Blog_Posts::create(
				$user['site_id'],
				$user['id'],
                $_POST['category_id'], 
                $_POST['title'], 
                $_POST['content'],
                String::tagify($_POST['title'])
            );
            
            Message::store('notify', 'Post created successfully.');
            Router::redirect('admin/blog/posts/manage');
        }
        
        View::load('Blog_Admin', 'blog_admin_posts_create',
            array('categories' => Blog_Categories::get_all()));
    }
    
    public static function edit($id) 
    {
        if($_POST)
        {
            Blog_Posts::update($id, $_POST['title'], $_POST['content'],
                String::tagify($_POST['title']));
            
            Message::store('notify', 'Post updated successfully.');
            Router::redirect('admin/blog/posts/manage');
        }
        
        View::load('Blog_Admin', 'blog_admin_posts_edit',
            array('post' => Blog_Posts::get_by_id($id)));
    }
    
    public static function delete($id) 
    {
        Blog_Posts::delete($id);
        
        Message::store('notify', 'Post deleted successfully.');
        Router::redirect('admin/blog/posts/manage');
    }

}
