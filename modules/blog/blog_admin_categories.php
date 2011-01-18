<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
/**
 * =============================================================================
 * Blog_Admin_Categories
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class Blog_Admin_Categories {

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function manage() 
    {
        View::load('Blog_Admin', 'blog_admin_categories_manage',
            array('categories' => Blog_Categories::get_all()));
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
            if(!Blog_Categories::exists($_POST['name']))
            {
                Blog_Categories::create($_POST['name'], 
                    String::tagify($_POST['name']));
                    
                Message::store('success', 'Category created successfully.');
                Router::redirect('admin/blog/categories/manage');
            }
            else
                Message::set('error', 'A category with that name already exists.');
        }
        
        View::load('Blog_Admin', 'blog_admin_categories_create');
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
            if(!Blog_Categories::exists($_POST['name']))
            {
                Blog_Categories::update($cid, $_POST['name'],
                    String::tagify($_POST['name']));
                    
                Message::store('success', 'Category updated successfully.');
                Router::redirect('admin/blog/categories/manage');
            }
            else
                Message::set('error', 'A category with that name already exists.');
        }
        
        View::load('Blog_Admin', 'blog_admin_categories_edit',
            array('category' => Blog_Categories::get_by_cid($cid)));
    }
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function delete($cid) 
    {
        Blog_Categories::delete($cid);
        Message::store('success', 'Category deleted successfully.');
        Router::redirect('admin/blog/categories/manage');
    }

}
