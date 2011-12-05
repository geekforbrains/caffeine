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
            'category' => Portfolio_Model_Categories::get_by_slug($category_slug),
            'items' => Portfolio_Model_Items::get_by_category_slug($category_slug)
        ));
    }

    public static function item($cidOrSlug)
    {
        if(is_numeric($cidOrSlug))
            $item = Portfolio_Model_Items::get_by_cid($cidOrSlug);
        else
            $item = Portfolio_Model_Items::get_by_slug($cidOrSlug);

        if($item)
        {
            View::load('Portfolio', 'item', array(
                'item' => $item
            ));
        }
        else
            return false;
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
