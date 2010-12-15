<?php
class Blog_Admin_Categories {

    public static function manage() 
    {
        View::load('Blog_Admin', 'blog_admin_categories_manage',
            array('categories' => Blog_Categories::get_all()));
    }
    
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
    
    public static function edit($id) 
    {
        if($_POST)
        {
            if(!Blog_Categories::exists($_POST['name']))
            {
                Blog_Categories::update($_POST['id'], $_POST['name'],
                    String::tagify($_POST['name']));
                    
                Message::store('success', 'Category updated successfully.');
                Router::redirect('admin/blog/categories/manage');
            }
            else
                Message::set('error', 'A category with that name already exists.');
        }
        
        View::load('Blog_Admin', 'blog_admin_categories_edit',
            array('category' => Blog_Categories::get_by_id($id)));
    }
    
    public static function delete($id) 
    {
        Blog_Categories::delete($id);
        Message::store('success', 'Category deleted successfully.');
        Router::redirect('admin/blog/categories/manage');
    }

}
