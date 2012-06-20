<?php

class User_AccountModel extends Model {

    public $_timestamps = true;

    public $_belongsTo = array('plan.plan');

    public $_hasMany = array('user.user');
    
    public $_fields = array(
        'subdomain' => array(
            'type' => 'varchar',
            'length' => 255,
            'not null' => true
        ),
        'status' => array( // trial, active, frozen, closed
            'type' => 'varchar',
            'length' => 10,
            'not null' => true
        ),
        'trial_ends' => array(
            'type' => 'int',
            'size' => 'big',
            'not null' => true
        )
    );

    public $_indexes = array('subdomain', 'status', 'trial_ends');

    public function getBySubdomain($subdomain) {
        return $this->where('subdomain', '=', $subdomain)->first();
    }

    public function isValidSubdomain($subdomain) {
        return (boolean) $this->where('subdomain', '=', $subdomain)->first();
    }

}
