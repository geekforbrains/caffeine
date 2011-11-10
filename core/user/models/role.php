<?php

class User_RoleModel extends Model {

    public $_hasAndBelongsToMany = array('user.user', 'user.permission');

    public $_fields = array(
        'name' => array(
            'type' => 'varchar',
            'length' => 255,
            'not null' => true
        )
    );

}
