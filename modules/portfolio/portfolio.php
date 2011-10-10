<?php

class Portfolio {

    public static function categories()
    {
        View::load('Portfolio', 'categories', array(
            'categories' => Portfolio_Model_Categories::get_all()
        ));
    }

    public static function items($category_slug)
    {
        View::load('Portfolio', 'items', array(
            'items' => Portfolio_Model_Items::get_by_category_slug($category_slug)
        ));
    }

    // Convenience method for custom views
    public static function get_categories() {
        return Portfolio_Model_Categories::get_all();
    }

    // Convenience method for custom views
    public static function get_items($category_cid) {
        return Portfolio_Model_Items::get_by_category_cid($category_cid);
    }

}
