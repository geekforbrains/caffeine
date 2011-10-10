<?php

class Portfolio_Admin_Categories {

    public static function manage()
    {
        View::load('Portfolio', 'admin/categories/manage', array(
            'categories' => Portfolio_Model_Categories::get_all()
        ));
    }

    public static function create()
    {
        if($_POST)
        {
            Validate::check('name', 'Name', array('required'));

            if(Validate::passed())
            {
                if(Portfolio_Model_Categories::create($_POST['name']))
                    Message::set(MSG_OK, 'Category created successfully.');
                else
                    Message::set(MSG_ERR, 'Error creating category. Please try again.');
            }
        }

        View::load('Portfolio', 'admin/categories/create');
    }

    public static function edit($cid)
    {
        if($_POST)
        {
            Validate::check('name', 'Name', array('required'));

            if(Validate::passed())
            {
                if(Portfolio_Model_Categories::update($cid, $_POST['name']))
                    Message::set(MSG_OK, 'Category updated successfully.');
                else
                    Message::set(MSG_ERR, 'Error updating category. Please try again.');
            }
        }

        View::load('Portfolio', 'admin/categories/edit', array(
            'category' => Portfolio_Model_Categories::get_by_cid($cid)
        ));
    }

    public static function delete($cid)
    {
        if(Portfolio_Model_Categories::delete($cid))
            Message::store(MSG_OK, 'Category deleted successfully.');
        else
            Message::store(MSG_ERR, 'Error deleting category. Please try again.');

        Router::redirect('admin/portfolio/categories/manage');
    }

}
