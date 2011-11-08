<?php

class User_UserModel extends Model {

    public $tableTimestamps = true;

    public $tableHasMany = array('blog.post');
    public $tableBelongsTo = array('user.account');

    public $tableFields = array(
        'email' => array(
            'type' => 'varchar',
            'length' => 255,
            'not null' => true
        ),
        'pass' => array(
            'type' => 'varchar',
            'length' => 32,
            'not null' => true
        )
    );

}
