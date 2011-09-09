<?php

class FAQ_Admin_Questions {

    public static function manage()
    {
        View::load('FAQ', 'admin/questions/manage', array(
            'questions' => FAQ_Model_Questions::get_all()
        ));
    }

    public static function create()
    {
        if($_POST)
        {
            Validate::check('category_cid', 'Category', array('required'));
            Validate::check('question', 'Question', array('required'));
            Validate::check('answer', 'Answer', array('required'));

            if(Validate::passed())
            {
                if(FAQ_Model_Questions::create($_POST))
                    Message::set(MSG_OK, 'Question created successfully.');
                else
                    Message::set(MSG_ERR, 'Error creating question. Please try again.');
            }
        }

        View::load('FAQ', 'admin/questions/create', array(
            'categories' => FAQ_Model_Categories::get_all()
        ));
    }

    public static function edit($cid)
    {
        if($_POST)
        {
            Validate::check('category_cid', 'Category', array('required'));
            Validate::check('question', 'Question', array('required'));
            Validate::check('answer', 'Answer', array('required'));

            if(Validate::passed())
            {
                if(FAQ_Model_Questions::update($cid, $_POST))
                    Message::set(MSG_OK, 'Question updated successfully.');
                else
                    Message::set(MSG_ERR, 'Error updating question. Please try again.');
            }
        }
        
        View::load('FAQ', 'admin/questions/edit', array(
            'categories' => FAQ_Model_Categories::get_all(),
            'question' => FAQ_Model_Questions::get_by_cid($cid)
        ));
    }

    public static function delete($cid)
    {
        if(FAQ_Model_Questions::delete($cid))
            Message::store(MSG_OK, 'Question deleted successfully.');
        else
            Message::store(MSG_ERR, 'Error deleting question. Please try again.');

        Router::redirect('admin/questions/manage');
    }

}
