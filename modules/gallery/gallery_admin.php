<?php 

class Gallery_Admin {

    public static function manage()
    {
        View::load('Gallery', 'admin/manage', array(
            'albums' => Gallery_Model::get_all_albums()
        ));
    }

    public static function create()
    {
        if($_POST)
        {
            Validate::check('name', 'Name', array('required'));

            if(Validate::passed())
            {
                if(Gallery_Model::create_album($_POST['name']))
                    Message::set(MSG_OK, 'Album created successfully.');
                else
                    Message::set(MSG_ERR, 'Error creating album. Please try again.');
            }
        }

        View::load('Gallery', 'admin/create');
    }

    public static function edit($cid)
    {
        if(isset($_POST['update_album']))
        {

        }

        if(isset($_POST['upload_photo']))
        {

        }

        View::load('Gallery', 'admin/edit', array(
            'album' => Gallery_Model::get_album_by_cid($cid),
            'photos' => Gallery_Model::get_photos_by_album_cid($cid)
        ));
    }
    
    public static function delete($cid)
    {

    }

}
