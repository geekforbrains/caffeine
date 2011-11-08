<?php

class User_AccountModel extends Model {

    public $tableTimestamps = true;

    public $tableHasMany = array('user.user');
    
    public $tableFields = array(
        'name' => array(
            'type' => 'varchar',
            'length' => 255,
            'not null' => true
        )
    );

}
