<?php

class Multilanguage_StringContentModel extends Model {

    public $_fields = array(
        'hash' => array(
            'type' => 'varchar',
            'length' => 32,
            'not null' => true
        ),
        'content' => array(
            'type' => 'text',
            'size' => 'normal',
            'not null' => true
        )
    );

    public $_indexes = array('hash');

}
