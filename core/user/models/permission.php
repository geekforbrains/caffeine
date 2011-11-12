<?php

class User_PermissionModel extends Model {

    public $_belongsTo = array('user.role');

    public $_fields = array(
        'permission' => array(
            'type' => 'varchar',
            'length' => 255,
            'not null' => true
        )
    );

}
