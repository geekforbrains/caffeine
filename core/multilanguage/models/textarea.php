<?php

class Multilanguage_TextareaModel extends Model {

    public $_belongsTo = array('multilanguage.content');

    public $_fields = array(
        'name' => array(
            'type' => 'varchar',
            'length' => 32,
            'not null' => true
        ),
        'content' => array(
            'type' => 'text',
            'size' => 'big',
            'not null' => true
        )
    );

    public $_indexes = array('name');

}
