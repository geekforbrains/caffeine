<?php

class Multilanguage_TextModel extends Model {

    public $_belongsTo = array('multilanguage.content');

    public $_fields = array(
        'name' => array(
            'type' => 'varchar',
            'length' => 32,
            'not null' => true
        ),
        'content' => array(
            'type' => 'varchar',
            'length' => 255,
            'not null' => true
        )
    );

    public $_indexes = array('name');

}
