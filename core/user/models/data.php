<?php

class User_DataModel extends Model {

    public $_belongsTo = array('user.user');

    public $_fields = array(
        'name' => array(
            'type' => 'varchar',
            'length' => 50,
            'not null' => true
        ),
        'value' => array(
            'type' => 'varchar',
            'length' => 255,
            'not null' => true
        )
    );

    public $_indexes = array('name', 'value');

}
