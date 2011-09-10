<?php 

class Gallery_Admin {

    public static function manage()
    {
        $albums = Gallery_Model_Albums::get_all();

        foreach($albums as &$a)
            $a['photo_count'] = Gallery_Model_Photos::get_count_by_album_cid($a['cid']);

        View::load('Gallery', 'admin/manage', array(
            'albums' => $albums
        ));
    }

    public static function create()
    {
        if($_POST)
        {
            Validate::check('name', 'Name', array('required'));

            if(Validate::passed())
            {
                if(Gallery_Model_Albums::create($_POST['name']))
                    Message::set(MSG_OK, 'Album created successfully.');
                else
                    Message::set(MSG_ERR, 'Error creating album. Please try again.');
            }
        }

        View::load('Gallery', 'admin/create');
    }

    public static function edit($album_cid)
    {
        if(isset($_POST['update_album']))
        {
            Validate::check('name', 'Name', array('required'));

            if(Validate::passed())
            {
                if(Gallery_Model_Albums::update($album_cid, $_POST['name']))
                    Message::set(MSG_OK, 'Album updated successfully.');
                else
                    Message::set(MSG_ERR, 'Error updating album. Please try again.');
            }
        }

        if(isset($_POST['upload_photo']))
        {
            $data = $_POST;

            if($media_cid = Media::add('photo'))
            {
                $data['album_cid'] = $album_cid;
                $data['media_cid'] = $media_cid; 

                if(Gallery_Model_Photos::create($data))
                    Message::set(MSG_OK, 'Photo uploaded successfully.');
                else
                    Message::set(MSG_ERR, 'Error uploading photo. Please try again.');
            }
            else
                Message::set(MSG_ERR, Media::error());
        }

        View::load('Gallery', 'admin/edit', array(
            'album' => Gallery_Model_Albums::get_by_cid($album_cid),
            'photos' => Gallery_Model_Photos::get_by_album_cid($album_cid)
        ));
    }

    public static function edit_photo($album_cid, $photo_cid)
    {
        if($_POST)
        {
            if(Gallery_Model_Photos::update($photo_cid, $_POST))
                Message::set(MSG_OK, 'Photo updated successfully.');
            else
                Message::set(MSG_ERR, 'Error updating photo. Please try agian.');
        }

        View::load('Gallery', 'admin/edit_photo', array(
            'photo' => Gallery_Model_Photos::get_by_cid($photo_cid)
        ));
    }

    public static function delete_photo($album_cid, $photo_cid)
    {
        if(Gallery_Model_Photos::delete($photo_cid))
            Message::store(MSG_OK, 'Photo deleted successfully.');
        else
            Message::store(MSG_ERR, 'Error deleting photo. Please try again.');

        Router::redirect('admin/gallery/edit/' . $album_cid);
    }
    
    public static function delete($cid)
    {
        if(Gallery_Model_Albums::delete($cid))
            Message::store(MSG_OK, 'Album deleted successfully.');
        else
            Message::store(MSG_ERR, 'Error deleting album. Please try again.');

        Router::redirect('admin/gallery/manage');
    }

}
