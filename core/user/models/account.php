<?php

class User_AccountModel extends Model {

    public $_timestamps = true;

    public $_hasMany = array('user.user');
    
    public $_fields = array(
        'name' => array(
            'type' => 'varchar',
            'length' => 255,
            'not null' => true
        )
    );

}
