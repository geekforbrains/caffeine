<?php

class Ads_Admin_Areas {

    public static function manage()
    {
        View::load('Ads', 'admin/areas/manage', array(
            'areas' => Ads_Model_Areas::get_all()
        ));
    }

    public static function create()
    {
        if($_POST)
        {
            Validate::check('name', 'Name', array('required'));
            Validate::check('slug', 'Slug', array('required'));
            Validate::check('width', 'Image Width', array('required'));
            Validate::check('height', 'Image Height', array('required'));

            if(Validate::passed())
            {
                if(Ads_Model_Areas::create($_POST))
                {
                    Message::store(MSG_OK, 'Area created successfully.');
                    Router::redirect('admin/ads/areas/manage');
                }
                else
                    Message::set(MSG_ERR, 'Error creating area. Please try again.');
            }
        }

        View::load('Ads', 'admin/areas/create');
    }   

    public static function edit($cid)
    {
        if($_POST)
        {
            Validate::check('name', 'Name', array('required'));
            Validate::check('slug', 'Slug', array('required'));
            Validate::check('width', 'Image Width', array('required'));
            Validate::check('height', 'Image Height', array('required'));

            if(Validate::passed())
            {
                if(Ads_Model_Areas::update($cid, $_POST))
                {
                    Message::store(MSG_OK, 'Area updated successfully.');
                    Router::redirect('admin/ads/areas/manage');
                }
                else
                    Message::set(MSG_ERR, 'Error updating area. Please try again.');
            }
        }

        View::load('Ads', 'admin/areas/edit', array(
            'area' => Ads_Model_Areas::get_by_cid($cid)
        ));
    }

    public static function delete($cid)
    {
        if(Ads_Model_Areas::delete($cid))
            Message::store(MSG_OK, 'Area deleted successfully.');
        else
            Message::store(MSG_ERR, 'Error deleting area. Please try again.');

        Router::redirect('admin/ads/areas/manage');
    }

}
