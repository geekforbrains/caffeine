<?php
class Store_Admin_Products {

    public static function manage()
    {
        $products = Store_Model_Products::get_all();
        foreach($products as &$p)
            $p['images'] = Store_Model_Products::get_photos_by_cid($p['cid']);

        View::load('Store', 'admin/products/manage', array(
            'products' => $products,
            'featured' => Store_Model_Products::get_featured()
        ));
    }

    public static function create()
    {
        if($_POST)
        {
            Validate::check('title', 'Title', array('required'));
            Validate::check('price', 'Price', array('required'));
            Validate::check('long_description', 'Detailed Description', array('required'));
            Validate::check('category_cid', 'Category', array('required'));
            Validate::check('shipping_size_cid', 'Shipping Size', array('required'));

            if(Validate::passed())
            {
                $_POST['slug'] = String::tagify($_POST['title']);
                $_POST['is_featured'] = (isset($_POST['is_featured'])) ? 1 : 0;
                $_POST['is_used'] = (isset($_POST['is_used'])) ? 1 : 0;

                if($product_cid = Store_Model_Products::create($_POST))
                {
                    // Add newly created product to categories
                    foreach($_POST['category_cid'] as $category_cid)
                        Store_Model_Products::add_to_category($product_cid, $category_cid);

                    Message::store(MSG_OK, 'Product created successfully.');
                    Router::redirect('admin/store/products/edit/' . $product_cid);
                }
                else
                    Message::set(MSG_ERR, 'Error creating product. Please try again.');
            }
        }

        MultiArray::load(Store_Model_Categories::get_all());
        $categories = MultiArray::indent();

        View::load('Store', 'admin/products/create', array(
            'categories' => $categories,
            'sizes' => Store_Model_Shipping::get_sizes()
        ));
    }

    public static function edit($cid)
    {
        if($_POST)
        {
            if(isset($_POST['update_product']))
            {
                // Create new product slug from title
                $_POST['slug'] = String::tagify($_POST['title']);

                $_POST['is_featured'] = (isset($_POST['is_featured'])) ? 1 : 0;
                $_POST['is_used'] = (isset($_POST['is_used'])) ? 1 : 0;

                // Update product info
                Store_Model_Products::update($cid, $_POST);

                // Update product categories
                Store_Model_Products::update_categories($cid, $_POST['category_cid']);

                Message::set(MSG_OK, 'Product updated successfully.');
            }

            if(isset($_POST['upload_photo']))
            {
                if($media_cid = Media::add('photo'))
                {
                    Store_Model_Products::add_photo($cid, $media_cid);
                    Message::set(MSG_OK, 'Photo uploaded successfully.');
                }
                else
                    Message::set(MSG_ERR, Media::error());
            }

            if(isset($_POST['upload_file']))
            {
                if($media_cid = Media::add('file'))
                {
                    Store_Model_Products::add_file($cid, $media_cid);
                    Message::set(MSG_OK, 'File uploaded successfully.');
                }
                else
                    Message::set(MSG_ERR, Media::error());
            }

            if(isset($_POST['create_option']))
            {
                if($option_cid = Store_Model_Products::create_option($cid, $_POST['option']))
                {
                    Message::store(MSG_OK, 'Option created successfully.');
                    Router::redirect('admin/store/products/edit/' . $cid . '/edit-option/' . $option_cid);
                }
                else
                    Message::set(MSG_ERR, 'Error creating option. Please try again.');
            }

            if(isset($_POST['add_related_product']))
            {
                Validate::check('related_product_cid', 'Product', array('required'));

                if(Validate::passed())
                {
                    if(!Store_Model_Products::is_related($cid, $_POST['related_product_cid']))
                    {
                        if(Store_Model_Products::add_related($cid, $_POST['related_product_cid']))
                            Message::set(MSG_OK, 'Related product added successfully.');
                        else
                            Message::set(MSG_ERR, 'Error adding related product. Please try again.');
                    }
                    else
                        Message::set(MSG_ERR, 'The product you selected is already related to this product.');
                }
            }
        }

        // Sort product categories into easily searchable array for comparison
        MultiArray::load(Store_Model_Categories::get_all());
        $categories = MultiArray::indent();

        $product_categories = Store_Model_Products::get_categories_by_cid($cid);
        $sorted_product_categories = array();
        foreach($product_categories as $pc)
            $sorted_product_categories[] = $pc['cid'];

        View::load('Store', 'admin/products/edit', array(
            'product' => Store_Model_Products::get_by_cid($cid),
            'product_categories' => $sorted_product_categories,
            'categories' => $categories,
            'sizes' => Store_Model_Shipping::get_sizes(),
            'images' => Store_Model_Products::get_photos_by_cid($cid),
            'files' => Store_Model_Products::get_files_by_cid($cid),
            'options' => Store_Model_Products::get_options_by_cid($cid),
            'products' => Store_Model_Products::get_all(),
            'related_products' => Store_Model_Products::get_related($cid)
        ));
    }

    public static function edit_option($product_cid, $option_cid)
    {
        if($_POST)
        {
            if(isset($_POST['update_option']))
            {
                if(Store_Model_Products::update_option($option_cid, $_POST['option']))
                    Message::set(MSG_OK, 'Option updated successfully.');
                else
                    Message::set(MSG_ERR, 'Error updating option, please try again.');
            }

            if(isset($_POST['add_value']))
            {
                //if(Store_Model_Products::add_option_value($option_cid, $_POST['value'], $_POST['price']))
                if(Store_Model_Products::add_option_value($option_cid, $_POST['value'], $_POST['price'], $_POST['sku']))
                    Message::set(MSG_OK, 'Option value added successfully.');
                else
                    Message::set(MSG_ERR, 'Error creating option, please try again.');
            }
        }

        View::load('Store', 'admin/products/edit_option', array(
            'product_cid' => $product_cid,
            'option' => Store_Model_Products::get_option_by_cid($option_cid),
            'values' => Store_Model_Products::get_values_by_option_cid($option_cid),
            'symbol' => Store_Model_Settings::get('symbol')
        ));
    }

    public static function delete_option($product_cid, $option_cid)
    {
        if(Store_Model_Products::delete_option($product_cid, $option_cid))
            Message::store(MSG_OK, 'Option deleted successfully.');
        else
            Message::store(MSG_ERR, 'Error deleting option, please try again.');

        Router::redirect('admin/store/products/edit/' .$product_cid);
    }

    public static function delete_value($product_cid, $option_cid, $value_cid)
    {
        if(Store_Model_Products::delete_value($option_cid, $value_cid))
            Message::store(MSG_OK, 'Value deleted successfully.');
        else
            Message::store(MSG_ERR, 'Error deleting value, please try again.');

        Router::redirect('admin/store/products/edit/' .$product_cid. '/edit-option/' .$option_cid);
    }

    public static function delete_photo($product_cid, $media_cid)
    {
        if(Store_Model_Products::delete_photo($product_cid, $media_cid))
            Message::store(MSG_OK, 'Photo deleted successfully.');
        else
            Message::store(MSG_ERR, 'Error deleting photo. Please try again.');

        Router::redirect('admin/store/products/edit/' . $product_cid);
    }

    public static function delete_file($product_cid, $media_cid)
    {
        if(Store_Model_Products::delete_file($product_cid, $media_cid))
            Message::store(MSG_OK, 'File deleted successfully.');
        else
            Message::store(MSG_ERR, 'Error deleting file. Please try again.');

        Router::redirect('admin/store/products/edit/' . $product_cid);
    }

    public static function delete_related($product_cid, $related_cid)
    {
        if(Store_Model_Products::delete_related($product_cid, $related_cid))
            Message::store(MSG_OK, 'Related product deleted successfully.');
        else
            Message::store(MSG_ERR, 'Error deleting related product. Please try again.');

        Router::redirect('admin/store/products/edit/' . $product_cid);
    }

    public static function delete($cid)
    {
        if(Store_Model_Products::delete($cid))
            Message::store(MSG_OK, 'Product deleted successfully.');
        else
            Message::store(MSG_ERR, 'Error deleting product. Please try again.');

        Router::redirect('admin/store/products');
    }

}
