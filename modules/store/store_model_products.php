<?php
class Store_Model_Products {
    
    // TODO This is just a temporary solution, needs love
    public static function get_all()
    {
        Database::query('SELECT * FROM {store_products} WHERE deleted = 0 ORDER BY title ASC');
        return Database::fetch_all();
    }

    public static function get_featured()
    {
        Database::query('SELECT * FROM {store_products} WHERE deleted = 0 AND is_featured = 1 ORDER BY title ASC');

        if(Database::num_rows() > 0)
        {
            $rows = Database::fetch_all();

            foreach($rows as &$row)
                $row['images'] = self::get_photos_by_cid($row['cid']);

            return $rows;
        }

        return array();
    }

    public static function get_by_cid($cid)
    {
        Database::query('SELECT * FROM {store_products} WHERE cid = %s', $cid);

        if(Database::num_rows() > 0)
            return Database::fetch_array();
        return false;
    }

    /**
     * -------------------------------------------------------------------------
     * Returns all products associated with a given category CID
     * -------------------------------------------------------------------------
     */
    public static function get_by_category_cid($category_cid)
    {
        Database::query('
            SELECT
                sp.*
            FROM {store_product_categories} spc 
                JOIN {store_products} sp ON sp.cid = spc.product_cid
            WHERE
                spc.category_cid = %s
                AND sp.deleted = 0
            GROUP BY
                sp.cid
            ORDER BY
                sp.title ASC
            ',
            $category_cid
        );

        return Database::fetch_all();
    }

    /**
     * -------------------------------------------------------------------------
     * This returns a list of popular products in the given category. The 
     * popularity is determined by the number of orders for products in this
     * category. If no orders have been placed for any products, a random list
     * of products will be returned.
     * -------------------------------------------------------------------------
     */
    public static function get_popular_by_category_cid($category_cid)
    {
        Database::query('
            SELECT DISTINCT
                sop.*
            FROM {store_order_products} sop
                JOIN {store_product_categories} spc ON spc.product_cid = sop.product_cid
            WHERE
                spc.category_cid = %s
            LIMIT 10
            ',
            $category_cid
        );

        $rows = Database::fetch_all();
        $products = array();

        // If we have some products that were ordered
        if($rows)
        {
            $sorted = array();

            foreach($rows as $r)
            {
                if(isset($sorted[$r['product_cid']]))
                    $sorted[$r['product_cid']] += 1;
                else
                    $sorted[$r['product_cid']] = 1;
            }

            arsort($sorted);

            foreach($sorted as $product_cid => $count)
            {
                $product = self::get_by_cid($product_cid);
                $product['images'] = self::get_photos_by_cid($product_cid);
                $products[] = $product;
            }
        }
        
        // Get random products as a buffer
        if(count($products) < 10)
        {
            // Make sure we dont get products already selected
            $where = '';
            if($products)
            {
                $where = 'AND (';
                foreach($products as $p)
                {
                    $where .= 'sp.cid != \'' . $p['cid'] . '\' AND ';
                }
                $where = rtrim($where, ' AND ');
                $where .= ')';
            }

            Database::query('
                SELECT
                    sp.*
                FROM {store_products} sp
                    JOIN {store_product_categories} spc ON spc.product_cid = sp.cid
                WHERE
                    spc.category_cid = %s
                    AND sp.deleted = 0
                    '.$where.'
                ORDER BY RAND()
                LIMIT 10
                ',
                $category_cid
            );

            $random_products = Database::fetch_all();
            foreach($random_products as &$p)
                $p['images'] = self::get_photos_by_cid($p['cid']);

            // Only return a maximum of 10 products after joining popular and random
            $products = array_slice(array_merge($products, $random_products), 0, 10);
        }

        return $products;
    }

    public static function get_categories_by_cid($cid)
    {
        Database::query('
            SELECT
                spc.product_cid,
                sc.*
            FROM {store_product_categories} spc 
                JOIN {store_categories} sc ON sc.cid = spc.category_cid
            WHERE
                spc.product_cid = %s
            ',
            $cid
        );

        return Database::fetch_all();
    }

    public static function get_photos_by_cid($cid)
    {
        Database::query('SELECT * FROM {store_product_photos} WHERE product_cid = %s', $cid);
        return Database::fetch_all();
    }

    public static function get_files_by_cid($cid)
    {
        Database::query('
            SELECT
                mf.*
            FROM {store_product_files} spf
                JOIN {media_files} mf ON mf.cid = spf.media_cid
            WHERE
                spf.product_cid = %s
            ',
            $cid
        );

        return Database::fetch_all();
    }

    public static function get_options_by_cid($product_cid)
    {
        Database::query('
            SELECT * FROM {store_product_option_types} WHERE product_cid = %s ORDER BY cid
            ', 
            $product_cid
        );

        return Database::fetch_all();
    }

    public static function get_option_by_cid($option_cid)
    {
        Database::query('SELECT * FROM {store_product_option_types} WHERE cid = %s', $option_cid);
        if(Database::num_rows() > 0)
            return Database::fetch_array();
        return false;
    }

    public static function get_value_by_cid($value_cid)
    {
        Database::query('
            SELECT 
                spo.*,
                spot.name AS type
            FROM {store_product_options} spo
                JOIN {store_product_option_types} spot ON spot.cid = spo.product_option_type_cid
            WHERE
                spo.cid = %s
            ',
            $value_cid
        );

        if(Database::num_rows() > 0)
            return Database::fetch_array();
        return false;
    }

    public static function get_values_by_option_cid($option_cid)
    {
        Database::query('   
            SELECT * FROM {store_product_options} 
            WHERE product_option_type_cid = %s
            ORDER BY
                price ASC,
                value ASC
            ', 
            $option_cid
        );

        return Database::fetch_all();
    }

    public static function get_shipping_size_by_cid($cid)
    {
        Database::query('SELECT shipping_size_cid FROM {store_products} WHERE cid = %s', $cid);
        if(Database::num_rows() > 0)
            return Database::fetch_single('shipping_size_cid');
        return false;
    }

    /**
     * -------------------------------------------------------------------------
     * Creates the initial fields of a new product. Photos etc. are added later.
     * $data is usually the values of $_POST being passed.
     * -------------------------------------------------------------------------
     */
    public static function create($data)
    {
        $cid = Content::create(STORE_TYPE_PRODUCT);
        $status = Database::insert('store_products', array(
            'cid' => $cid,
            'shipping_size_cid' => $data['shipping_size_cid'],
            'title' => $data['title'],
            'slug' => $data['slug'],
            'blurb' => $data['blurb'],
            'short_description' => $data['short_description'],
            'long_description' => $data['long_description'],
            'sku' => $data['sku'],
            'price' => $data['price'],
            'is_used' => $data['is_used']
        ));

        if($status)
            return $cid;
        return false;
    }

    public static function add_to_category($product_cid, $category_cid)
    {
        Database::insert('store_product_categories', array(
            'product_cid' => $product_cid,
            'category_cid' => $category_cid
        ));
    }

    public static function add_photo($product_cid, $media_cid)
    {
        return Database::insert('store_product_photos', array(
            'product_cid' => $product_cid,
            'media_cid' => $media_cid
        ));
    }

    public static function add_file($product_cid, $media_cid)
    {
        return Database::insert('store_product_files', array(
            'product_cid' => $product_cid,
            'media_cid' => $media_cid
        ));
    }

    public static function update($cid, $data)
    {
        return Database::update('store_products',
            array(
                'shipping_size_cid' => $data['shipping_size_cid'],
                'title' => $data['title'],
                'slug' => $data['slug'],
                'price' => $data['price'],
                'sku' => $data['sku'],
                'blurb' => $data['blurb'],
                'short_description' => $data['short_description'],
                'long_description' => $data['long_description'],
                'is_featured' => $data['is_featured'],
                'is_used' => $data['is_used']
            ),
            array(
                'cid' => $cid
            )
        );
    }

    public static function update_categories($cid, $categories)
    {
        // First clear all categories for this product
        Database::delete('store_product_categories', array('product_cid' => $cid));

        // Add new categories
        foreach($categories as $category_cid)
        {
            Database::insert('store_product_categories', array(
                'product_cid' => $cid,
                'category_cid' => $category_cid
            ));
        }
    }

    public static function delete_photo($product_cid, $media_cid)
    {
        Media::delete($media_cid);
        return Database::delete('store_product_photos', array(
            'product_cid' => $product_cid,
            'media_cid' => $media_cid
        ));
    }

    public static function delete_file($product_cid, $media_cid)
    {
        Media::delete($media_cid);
        return Database::delete('store_product_files', array(
            'product_cid' => $product_cid,
            'media_cid' => $media_cid
        ));
    }

    public static function create_option($product_cid, $option)
    {
        $cid = Content::create(STORE_TYPE_OPTION_TYPE);
        $status = Database::insert('store_product_option_types', array(
            'cid' => $cid,
            'product_cid' => $product_cid,
            'name' => $option
        ));

        if($status)
            return $cid;
        return false;
    }

    public static function update_option($option_cid, $option)
    {
        Content::update($option_cid);
        return Database::update('store_product_option_types',
            array('name' => $option),
            array('cid' => $option_cid)
        );
    }

    public static function add_option_value($option_cid, $value, $price, $sku)
    {
        $cid = Content::create(STORE_TYPE_OPTION);
        $status = Database::insert('store_product_options', array(
            'cid' => $cid,
            'product_option_type_cid' => $option_cid,
            'value' => $value,
            'price' => $price,
            'sku' => $sku
        ));

        if($status)
            return $cid;
        return false;
    }

    public static function delete_value($option_cid, $value_cid)
    {
        Content::delete($value_cid);
        return Database::delete('store_product_options', array(
            'cid' => $value_cid,
            'product_option_type_cid' => $option_cid
        ));
    }

    public static function delete_option($product_cid, $option_cid)
    {
        // Delete values associated with option type
        $values = self::get_values_by_option_cid($option_cid);
        foreach($values as $v)
            self::delete_value($option_cid, $v['cid']);

        Content::delete($option_cid);
        return Database::delete('store_product_option_types', array(
            'cid' => $option_cid,
            'product_cid' => $product_cid
        ));
    }

    public static function delete($cid)
    {
        // Check if product has been ordered
        Database::query('SELECT * FROM {store_order_products} WHERE product_cid = %s', $cid);

        // If it has, only mark as deleted  
        if(Database::num_rows() > 0)
        {
            return Database::update('store_products',
                array('deleted' => '1'),
                array('cid' => $cid)
            );
        }

        // Otherwise, fully remove records
        else
        {
            Database::delete('store_product_categories', array('product_cid' => $cid));
            Database::delete('store_product_photos', array('product_cid' => $cid));
            Database::delete('store_product_option_types', array('product_cid' => $cid));
            return Database::delete('store_products', array('cid' => $cid));
        }
    }
    
    /**
     * Add a related product to the given product cid
     */
    public static function add_related($cid, $related_product_cid)
    {
        return Database::insert('store_related_products', array(
            'product_cid' => $cid,
            'related_product_cid' => $related_product_cid
        ));
    }

    public static function get_related($cid)
    {
        Database::query('
            SELECT
                sp.*
            FROM {store_related_products} srp
                JOIN {store_products} sp ON sp.cid = srp.related_product_cid
            WHERE
                srp.product_cid = %s
            ',
            $cid
        );

        if(Database::num_rows() > 0)
        {
            $rows = Database::fetch_all();

            foreach($rows as &$row)
                $row['images'] = self::get_photos_by_cid($row['cid']);

            return $rows;
        }

        return false;
    }

    public static function is_related($cid, $related_cid)
    {
        Database::query('
            SELECT * FROM {store_related_products} 
            WHERE product_cid = %s and related_product_cid = %s', 
            $cid, $related_cid
        );

        if(Database::num_rows() > 0)
            return true;
        return false;
    }

    public static function delete_related($cid, $related_cid)
    {
        return Database::delete('store_related_products', array(
            'product_cid' => $cid,
            'related_product_cid' => $related_cid
        ));
    }

}
