<?php

class Dev_MessageModel extends Model {


    public $_timestamps = true;


    public $_fields = array(
        'module' => array(
            'type' => 'varchar',
            'length' => 25,
            'not null' => true
        ),
        'message' => array(
            'type' => 'text',
            'not null' => true
        )
    );


}
