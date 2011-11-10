<?php

class Message_MessageModel extends Model {

    public $_fields = array(
        'type' => array(
            'type' => 'varchar',
            'length' => 10,
            'not null' => true
        ),
        'message' => array(
            'type' => 'varchar',
            'length' => 255,
            'not null' => true
        )
    );

    public $_indexes = array('type');

}
