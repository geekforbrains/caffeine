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
            array('categories' => Blog_Model_Categories::get_all()));
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
			Validate::check('name', 'Name', array('required'));

			if(Validate::passed())
			{
				if(!Blog_Model_Categories::exists($_POST['name']))
				{
					Blog_Model_Categories::create($_POST['name'], 
						String::tagify($_POST['name']));
						
					Message::store(MSG_OK, 'Category created successfully.');
					Router::redirect('admin/blog/categories/manage');
				}
				else
					Message::set(MSG_ERR, 'A category with that name already exists.');
			}
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
		if(!Blog_Model_Categories::get_by_cid($cid))
			Router::redirect('admin/blog/categories');

        if($_POST)
        {
			Validate::check('name', 'Name', array('required'));
			Validate::check('slug', 'Slug', array('required'));

			if(Validate::passed())
			{
				if(!Blog_Model_Categories::exists($_POST['name']))
				{
					Blog_Model_Categories::update(
						$cid, 
						$_POST['name'],
						$_POST['slug']
					);
						
					Message::store(MSG_OK, 'Category updated successfully.');
					Router::redirect('admin/blog/categories/manage');
				}
				else
					Message::set(MSG_ERR, 'A category with that name already exists.');
			}
        }
        
        View::load('Blog_Admin', 'blog_admin_categories_edit',
            array('category' => Blog_Model_Categories::get_by_cid($cid)));
    }
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
    public static function delete($cid) 
    {
        if(Blog_Model_Categories::delete($cid))
			Message::store(MSG_OK, 'Category deleted successfully.');
		else
			Message::store(MSG_ERR, 'Unkown error when deleting category. Please try again.');

        Router::redirect('admin/blog/categories/manage');
    }

}
