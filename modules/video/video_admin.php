<?php

class Video_Admin {

    public static function manage()
    {
        View::load('Video', 'admin/manage', array(
            'albums' => Video_Model_Albums::get_all()
        ));
    }

    public static function create()
    {
        if($_POST)
        {
            Validate::check('name', 'Name', array('required'));

            if(Validate::passed())
            {
                if(Video_Model_Albums::create($_POST['name']))
                    Message::set(MSG_OK, 'Album created successfully.');
                else
                    Message::set(MSG_ERR, 'Error creating album. Please try again.');
            }
        }

        View::load('Video', 'admin/create');
    }

    public static function edit($album_cid)
    {
        if(isset($_POST['update_album']))
        {
            if(Video_Model_Albums::update($album_cid, $_POST['name']))
                Message::set(MSG_OK, 'Album updated successfully.');
        }

        if(isset($_POST['add_video']))
        {
            Validate::check('url', 'URL', array('required'));

            if(Validate::passed())
            {
                $data = Video::get_data($_POST['url']);

                if($data)
                {
                    $media_cid = Media::add_from_url($data['thumbnail']);

                    if($media_cid)
                    {
                        if(Video_Model::create($_POST['url'], $album_cid, $media_cid, $data))
                            Message::set(MSG_OK, 'Video added successfully.');
                        else
                            Message::set(MSG_ERR, 'Error creating video. Please try agian.');
                    }
                    else
                        Message::set(MSG_ERR, Media::error());
                }
                else
                    Message::set(MSG_ERR, 'Invalid video URL. Only Youtube or Vimeo URLs are supported.');
            }
        }

        View::load('Video', 'admin/edit', array(
            'album' => Video_Model_Albums::get_by_cid($album_cid),
            'videos' => Video_Model::get_by_album_cid($album_cid)
        ));
    }

    public static function delete_video($album_cid, $video_cid)
    {
        if(Video_Model::delete($video_cid))
            Message::store(MSG_OK, 'Video deleted successfully.');
        else
            Message::store(MSG_ERR, 'Error deleting video. Please try again.');

        Router::redirect('admin/video/edit/' . $album_cid);
    }

    public static function delete($cid)
    {
        if(Video_Model_Albums::delete($cid))
            Message::store(MSG_OK, 'Album deleted successfully.');
        else
            Message::store(MSG_ERR, 'Error deleting album. Please try again.');
        
        Router::redirect('admin/video/manage');
    }

}
