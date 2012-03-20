<?php

class Media_UrlModel extends Model {

    public $_timestamps = true;

    public $_fields = array(
        'url' => array(
            'type' => 'text',
            'size' => 'normal',
            'not null' => true
        ),
        'data' => array(
            'type' => 'text',
            'size' => 'normal',
            'not null' => true
        )
    );
    
}
