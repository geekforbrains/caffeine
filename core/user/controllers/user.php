<?php

class User_UserController extends Controller {

    public static function register()
    {
        $post = Input::clean($_POST);

        Validate::check($post['subdomain'], array('required'), 'subdomain');
        Validate::check($post['email'], array('required', 'email'), 'email');
        Validate::check($post['password'], array('required', 'min:4'), 'password');
        Validate::check($post['confirm_password'], array('required', 'matches:password'), 'confirm_password');

        if(Validate::getErrors())
            die('<pre>' . print_r(Validate::getErrors(), true));

        if(User::account()->where('subdomain', 'LIKE', $post['subdomain'])->first())
            die('subdomain already in use.');
        
        $accountId = User::account()->insert(array(
            'plan_id' => Plan::plan()->getDefault()->id,
            'subdomain' => $post['subdomain'],
            'status' => 'trial',
            'trial_ends' => strtotime(Config::get('user.trial_period'))
        ));

        if($accountId)
        {
            $userId = User::user()->insert(array(
                'account_id' => $accountId,
                'email' => $post['email'],
                'pass' => md5($post['password'])
            ));

            if($userId)
                die('account created');
            
            die('epic fail!');
        }
        else
            die('error creating account.');
    }

}
