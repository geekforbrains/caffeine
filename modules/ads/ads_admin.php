<?php

class Ads_Admin {

    public static function manage()
    {
        View::load('Ads', 'admin/ads/manage', array(
            'ads' => Ads_Model::get_all()
        ));
    }

    public static function create()
    {
        if($_POST)
        {
            Validate::check('name', 'Name', array('required'));
            Validate::check('area_cid', 'Area', array('required'));
            Validate::check('url', 'URL', array('required'));

            if(Validate::passed())
            {
                if($media_cid = Media::add('image'))
                {
                    $data = $_POST;
                    $data['media_cid'] = $media_cid;

                    if(Ads_Model::create($data))
                    {
                        Message::store(MSG_OK, 'Ad created successfully.');
                        Router::redirect('admin/ads/ads/manage');
                    }
                    else
                        Message::set(MSG_ERR, 'Error creating ad. Please try again.');
                }
                else
                    Message::set(MSG_ERR, Media::error());
            }
        }

        View::load('Ads', 'admin/ads/create', array(
            'areas' => Ads_Model_Areas::get_all()
        ));
    }

    public static function edit($cid)
    {
        if($_POST)
        {
            Validate::check('name', 'Name', array('required'));
            Validate::check('area_cid', 'Area', array('required'));
            Validate::check('url', 'URL', array('required'));

            if(Validate::passed())
            {
                $data = $_POST;
                if($media_cid = Media::add('image'))
                {
                    Media::delete($data['media_cid']); // Delete old image
                    $data['media_cid'] = $media_cid;
                }

                if(Ads_Model::update($cid, $data))
                    Message::set(MSG_OK, 'Ad updated successfully.');
                else
                    Message::set(MSG_ERR, 'Error updating ad. Please try again.');
            }
        }

        View::load('Ads', 'admin/ads/edit', array(
            'ad' => Ads_Model::get_by_cid($cid),
            'areas' => Ads_Model_Areas::get_all()
        ));
    }

    public static function delete($cid)
    {
        if(Ads_Model::delete($cid))
            Message::store(MSG_OK, 'Ad deleted successfully.');
        else
            Message::store(MSG_ERR, 'Error deleting ad. Please try again.');

        Router::redirect('admin/ads/ads/manage');
    }

}
