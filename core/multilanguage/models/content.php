<?php

class Multilanguage_ContentModel extends Model {

    public $_timestamps = true;

    public $_belongsTo = array('multilanguage.language');

    public $_fields = array(
        'module' => array(
            'type' => 'varchar',
            'length' => 32,
            'not null' => true
        ),
        'type' => array(
            'type' => 'varchar',
            'length' => 32,
            'not null' => true
        ),
        'type_id' => array(
            'type' => 'int',
            'size' => 'big',
            'not null' => true
        ),
    );

    public $_indexes = array('module', 'type', 'type_id');

}
