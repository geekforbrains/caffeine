<?php

class FAQ {

    public static function view()
    {
        $categories = FAQ_Model_Categories::get_all();

        foreach($categories as &$c)
            $c['questions'] = FAQ_Model_Questions::get_by_category_cid($c['cid']);

        View::load('FAQ', 'view', array(
            'categories' => $categories,
            'questions' => FAQ_Model_Questions::get_all()
        ));
    }

}
