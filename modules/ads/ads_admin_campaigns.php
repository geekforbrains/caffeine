<?php

class Ads_Admin_Campaigns {

    public static function manage()
    {
        View::load('Ads', 'admin/campaigns/manage', array(
            'active' => Ads_Model_Campaigns::get_by_status('active'),
            'scheduled' => Ads_Model_Campaigns::get_by_status('scheduled'),
            'completed' => Ads_Model_Campaigns::get_by_status('complete', 5)
        ));
    }

    public static function create()
    {
        if($_POST)
        {
            Validate::check('ad_cid', 'Ad', array('required'));
            Validate::check('start_date', 'Start Date', array('required'));

            if(Validate::passed())
            {
                $data = $_POST;

                $data['start_date'] = strtotime($data['start_date']);
                $data['end_date'] = strlen($data['end_date']) ? strtotime($data['end_date']) : 0; 
                $data['status'] = $data['start_date'] <= strtotime('today') ? 'active' : 'scheduled';

                if(Ads_Model_Campaigns::create($data))
                {
                    Message::store(MSG_OK, 'Campaign created successfully.');
                    Router::redirect('admin/ads/campaigns/manage');
                }
                else
                    Message::set(MSG_ERR, 'Error creating campaign. Please try again.');
            }
        }

        View::load('Ads', 'admin/campaigns/create', array(
            'ads' => Ads_Model::get_all()
        ));
    }

    public static function details($cid)
    {
        View::load('Ads', 'admin/campaigns/details', array(
            'campaign' => Ads_Model_Campaigns::get_by_cid($cid),
            'stats' => Ads_Model_Campaigns::get_stats($cid)
        ));
    }

    public static function stop($cid)
    {

    }

}
