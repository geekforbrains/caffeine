<?php

class User_UserModel extends Model {

    public $_timestamps = true;

    public $_belongsTo = array('user.account');

    public $_hasAndBelongsToMany = array('user.role');

    public $_fields = array(
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
