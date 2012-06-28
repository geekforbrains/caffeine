<?php

class User_UserModel extends Model {

    public $_timestamps = true;

    public $_belongsTo = array('user.account');

    public $_hasAndBelongsToMany = array('user.role');

    public $_fields = array(
        'email' => array(
            'type' => 'varchar',
            'length' => 255,
            'not null' => true,
        ),
        'pass' => array(
            'type' => 'varchar',
            'length' => 32,
            'not null' => true,
        ),
        'reset_token' => array(
            'type' => 'varchar',
            'length' => 32,
            'not null' => true
        ),
        'is_admin' => array(
            'type' => 'int',
            'length' => 1,
            'not null' => true,
            'default' => 0
        )
    );

    public $_indexes = array('reset_token', 'is_admin');

    public function validate($email, $pass)
    {
        return $this->where('email', '=', $email)
            ->andWhere('pass', '=', md5($pass))
            ->first();
    }

}
