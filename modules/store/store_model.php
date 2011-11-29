<?php
class Store_Model {
    
    public static function get_recent_products($limit = 10)
    {
        Database::query('
            SELECT
                sp.*,
                c.created
            FROM {store_products} sp
                LEFT JOIN {content} c ON c.id = sp.cid
            ORDER BY
                c.created
        ');

        $products = Database::fetch_all();
        
        // Add photos
        foreach($products as &$p)
            $p['images'] = Store_Model_Products::get_photos_by_cid($p['cid']);

        return $products;
    }

    public static function get_product_by_slug($slug)
    {
        Database::query('SELECT * FROM {store_products} WHERE slug LIKE %s', $slug);
        
        if(Database::num_rows() > 0)
        {
            $product = Database::fetch_array();
            $product['images'] = Store_Model_Products::get_photos_by_cid($product['cid']);
            return $product;
        }

        return false;
    }

    /**
     * -------------------------------------------------------------------------
     * Gets all product options and adds its values as a key array to the option
     * itself. This is to make it easier to loop through in front facing views.
     * -------------------------------------------------------------------------
     */
    public static function get_product_options_by_cid($cid)
    {
        $options = Store_Model_Products::get_options_by_cid($cid);
        foreach($options as &$o)
            $o['values'] = Store_Model_Products::get_values_by_option_cid($o['cid']);

        return $options;
    }

    public static function search($keywords)
    {
        Database::query('SELECT * FROM {store_products} WHERE MATCH (title, short_description) AGAINST (%s)', $keywords);
        $results = Database::fetch_all();

        // Add photos
        foreach($results as &$p)
            $p['images'] = Store_Model_Products::get_photos_by_cid($p['cid']);

        return $results;
    }

}
