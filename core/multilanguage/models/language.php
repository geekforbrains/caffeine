<?php

class Multilanguage_LanguageModel extends Model {

    public $_fields = array(
        'name' => array(
            'type' => 'varchar',
            'length' => 255,
            'not null' => true
        ),
        'code' => array(
            'type' => 'varchar',
            'length' => 3,
            'not null' => true
        )
    );

    public $_indexes = array('code');

}
