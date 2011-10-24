<?php

class Courses_Admin_Categories {

    public static function manage()
    {
        View::load('Courses', 'admin/categories/manage', array(
            'categories' => Courses_Model_Categories::get_all()
        ));
    }

    public static function create()
    {
        if($_POST)
        {
            $check = array('required');
            Validate::check('name', 'Name', $check);
            Validate::check('short_desc', 'Short Description', $check);
            Validate::check('long_desc', 'Long Description', $check);

            if(Validate::passed())
            {
                if(Courses_Model_Categories::create($_POST))
                {
                    Message::set(MSG_OK, 'Category created successfully.');
                    $_POST = array(); // Clear fields
                }
                else
                    Message::set(MSG_ERR, 'Error creating category. Please try again.');
            }
        }

        View::load('Courses', 'admin/categories/create');
    }

    public static function edit($cid)
    {
        if($_POST)
        {
            $check = array('required');
            Validate::check('name', 'Name', $check);
            Validate::check('short_desc', 'Short Description', $check);
            Validate::check('long_desc', 'Long Description', $check);

            if(Validate::passed())
            {
                if(Courses_Model_Categories::update($cid, $_POST))
                    Message::set(MSG_OK, 'Category updated successfully.');
                else
                    Message::set(MSG_ERR, 'Error updating category. Please try again.');
            }
        }

        View::load('Courses', 'admin/categories/edit', array(
            'category' => Courses_Model_Categories::get_by_cid($cid)
        ));
    }

    public static function delete($cid)
    {
        if(Courses_Model_Categories::delete($cid))
            Message::store(MSG_OK, 'Category deleted successfully.');
        else
            Message::store(MSG_ERR, 'Error deleting category. Please try again.');

        Router::redirect('admin/courses/categories/manage');
    }

}
