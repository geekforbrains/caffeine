<?php

class FAQ_Model_Questions {
    
    public static function get_all()
    {
        Database::query('SELECT * FROM {faq}');
        return Database::fetch_all();
    }

    public static function get_by_cid($cid)
    {
        Database::query('SELECT * FROM {faq} WHERE cid = %s', $cid);

        if(Database::num_rows() > 0)
            return Database::fetch_array();
        return false;
    }

    public static function get_by_category_cid($category_cid)
    {
        Database::query('SELECT * FROM {faq} WHERE category_cid = %s', $category_cid);
        return Database::fetch_all();
    }

    public static function create($data)
    {
        $cid = Content::create(FAQ_TYPE_QUESTION);
        $status = Database::insert('faq', array(
            'cid' => $cid,
            'category_cid' => $data['category_cid'],
            'question' => $data['question'],
            'answer' => $data['answer']
        ));

        if($status)
            return $cid;
        return false;
    }

    public static function update($cid, $data)
    {
        Content::update($cid);
        return Database::update('faq',
            array(
                'category_cid' => $data['category_cid'],
                'question' => $data['question'],
                'answer' => $data['answer']
            ),
            array('cid' => $cid)
        );
    }

    public static function delete($cid)
    {
        Content::delete($cid);
        return Database::delete('faq', array('cid' => $cid));
    }

}
