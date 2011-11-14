<?php

class Cache_CacheModel extends Model {

    public $_fields = array(
        'key_hash' => array(
            'type' => 'varchar',
            'length' => 32,
            'not null' => true
        ),
        'data' => array(
            'type' => 'text',
            'size' => 'big',
            'not null' => true
        ),
        'expires_on' => array(
            'type' => 'int',
            'size' => 'big',
            'unsigned' => true,
            'not null' => true
        )
    );

    public $_indexes = array('key_hash', 'expires_on');

}
