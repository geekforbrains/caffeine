<?php

class Variable_DataModel extends Model {

    public $_fields = array(
        'data_key' => array(
            'type' => 'varchar',
            'length' => 255,
            'not null' => true
        ),
        'data_value' => array(
            'type' => 'varchar',
            'length' => 255,
            'not null' => true
        )
    );

    public $_indexes = array('data_key');

}
