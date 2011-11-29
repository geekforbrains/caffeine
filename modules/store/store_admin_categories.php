<?php
class Store_Admin_Categories {

    public static function manage()
    {
        // Create new category
        if($_POST)
        {
            Validate::check('name', 'Category Name', array('required'));

            if(Validate::passed())
            {
                $slug = String::tagify($_POST['name']);

                // TODO Add "if exists" check
                if(Store_Model_Categories::create($_POST['parent_cid'], $_POST['name'], $slug))
                    Message::set(MSG_OK, 'Category created successfully.');
                else
                    Message::set(MSG_ERR, 'Error creating category. Please try again.');
            }
        }

        MultiArray::load(Store_Model_Categories::get_all());
        $categories = MultiArray::indent();

        View::load('Store', 'admin/categories/manage', array(
            'categories' => $categories
        ));
    }

    public static function edit($cid)
    {
        if($_POST)
        {
            Validate::check('name', 'Category Name', array('required'));
            Validate::check('slug', 'Category Slug', array('required'));

            if(Validate::passed())
            {
                if(Store_Model_Categories::update($cid, $_POST['parent_cid'], $_POST['name'], $_POST['slug']))
                {
                    Message::store(MSG_OK, 'Category updated successfully.');
                    Router::redirect('admin/store/categories/manage');
                }   
                else
                    Message::set(MSG_ERR, 'Error updating category. Please try again.');
            }
        }

        View::load('Store', 'admin/categories/edit', array(
            'categories' => Store_Model_Categories::get_all(),
            'category' => Store_Model_Categories::get_by_cid($cid)
        ));
    }

    public static function delete($cid)
    {
        if(Store_Model_Categories::delete($cid))
            Message::store(MSG_OK, 'Category deleted successfully.');
        else
            Message::store(MSG_ERR, 'Error deleting category. Please try again.');

        Router::redirect('admin/store/categories');
    }

}
