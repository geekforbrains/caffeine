<?php return array(

    'configs' => array(
        'user.login_success_redirect' => '',
        'user.access_denied_redirect' => ''
    ),

    'routes' => array(
        'admin/login' => array(
            'title' => 'Login',
            'callback' => array('user_admin', 'login'),
            'hidden' => true
        )
    )

);
