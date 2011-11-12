<?php

class User_RoleModel extends Model {

    public $_hasMany = array('user.permission');

    public $_hasAndBelongsToMany = array('user.user');

    public $_fields = array(
        'name' => array(
            'type' => 'varchar',
            'length' => 255,
            'not null' => true
        )
    );

}
