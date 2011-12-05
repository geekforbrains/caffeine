<?php

class Portfolio_Admin_Items {

    public static function manage()
    {
        View::load('Portfolio', 'admin/items/manage', array(
            'items' => Portfolio_Model_Items::get_all()
        ));
    }

    public static function create()
    {
        if($_POST)
        {
            Validate::check('category_cid', 'Category', array('required'));
            Validate::check('name', 'Name', array('required')); 

            if(Validate::passed())
            {
                if($date = strtotime($_POST['date']))
                {
                    if($thumb_cid = Media::add('thumb'))
                    {
                        $slug = String::tagify($_POST['name']); 

                        if($cid = Portfolio_Model_Items::create($_POST['category_cid'], $_POST['name'], $_POST['description'], $slug, $thumb_cid))
                        {
                            // Add extra data fields
                            Portfolio_Model_Items::add_data($cid, 'client', $_POST['client']);
                            Portfolio_Model_Items::add_data($cid, 'role', $_POST['role']);
                            Portfolio_Model_Items::add_data($cid, 'date', $date);

                            $_POST = array(); // Clear form
                            Message::set(MSG_OK, 'Item created successfully.');
                        }
                        else
                            Message::set(MSG_ERR, 'Error creating item. Please try again.');
                    }
                    else
                        Message::set(MSG_ERR, Media::error());
                }
                else
                    Message::set(MSG_ERR, 'Invalid date format in "Date" field.');
            }
        }

        View::load('Portfolio', 'admin/items/create', array(
            'categories' => Portfolio_Model_Categories::get_all()
        ));
    }

    public static function edit($cid)
    {
        if(isset($_POST['update_item']))
        {
            Validate::check('category_cid', 'Category', array('required'));
            Validate::check('name', 'Name', array('required')); 
            Validate::check('slug', 'Slug', array('required')); 

            if(Validate::passed())
            {
                if($date = strtotime($_POST['date']))
                {
                    $thumb_cid = ($_FILES['thumb']['size'] > 0) ? Media::add('thumb') : 0; 

                    Portfolio_Model_Items::update($cid, $_POST['category_cid'], $_POST['name'], $_POST['description'], $_POST['slug'], $thumb_cid);
                    Portfolio_Model_Items::add_data($cid, 'client', $_POST['client']);
                    Portfolio_Model_Items::add_data($cid, 'role', $_POST['role']);
                    Portfolio_Model_Items::add_data($cid, 'date', strtotime($_POST['date']));

                    Message::set(MSG_OK, 'Item updated successfully.');
                }
                else
                    Message::set(MSG_ERR, 'Invalid date in "Date" field.');
            }
        }

        if(isset($_POST['upload_photo']))
        {
            if($media_cid = Media::add('photo'))
            {
                if(Portfolio_Model_Items::add_photo($cid, $media_cid))
                    Message::set(MSG_OK, 'Photo uploaded added successfully.');
                else
                {
                    Media::delete($media_cid);
                    Message::set(MSG_ERR, 'Error adding photo. Please try again.');
                }
            }
            else
                Message::set(MSG_ERR, Media::error());
        }

        if(isset($_POST['add_video']))
        {
            Validate::check('url', 'Video URL', array('required'));

            if(Validate::passed())
            {
                $data = Video::get_data($_POST['url']);

                if($data)
                {
                    $media_cid = Media::add_from_url($data['thumbnail']);

                    if($media_cid)
                    {
                        if($video_cid = Video_Model::create($_POST['url'], 0, $media_cid, $data)) // 0 for albums (we dont want to use them)
                        {
                            Portfolio_Model_Items::add_video($cid, $video_cid);
                            Message::set(MSG_OK, 'Video added successfully.');
                        }
                        else
                            Message::set(MSG_ERR, 'Error adding video. Please try agian.');
                    }
                    else
                        Message::set(MSG_ERR, Media::error());
                }
                else
                    Message::set(MSG_ERR, 'Invalid video URL. Only Youtube or Vimeo URLs are supported.');
            }
        }

        View::load('Portfolio', 'admin/items/edit', array(
            'item' => Portfolio_Model_Items::get_by_cid($cid),
            'categories' => Portfolio_Model_Categories::get_all(),
            'photos' => Portfolio_Model_Items::get_photos_by_cid($cid),
            'videos' => Portfolio_Model_Items::get_videos_by_cid($cid)
        ));
    }

    public static function delete($cid)
    {
        if(Portfolio_Model_Items::delete($cid))
            Message::store(MSG_OK, 'Item deleted successfully. Please try again.');
        else
            Message::store(MSG_ERR, 'Error deleting item. Please try again.');

        Router::redirect('admin/portfolio/items/manage');
    }

    public static function delete_photo($item_cid, $media_cid)
    {
        if(Portfolio_Model_Items::delete_photo($item_cid, $media_cid))
            Message::store(MSG_OK, 'Photo deleted successfully.');
        else
            Message::store(MSG_ERR, 'Error deleting photos. Please try again.');

        Router::redirect('admin/portfolio/items/edit/' . $item_cid);
    }

    public static function delete_video($item_cid, $video_cid)
    {
        if(Portfolio_Model_Items::delete_video($item_cid, $video_cid))
            Message::store(MSG_OK, 'Video deleted successfully.');
        else
            Message::store(MSG_ERR, 'Error deleting video. Please try again.');

        Router::redirect('admin/portfolio/items/edit/' . $item_cid);
    }

}
