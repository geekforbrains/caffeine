<?php

class Multilanguage_FileModel extends Model {

    public $_belongsTo = array('multilanguage.content', 'media.file');

    public $_fields = array(
        'name' => array(
            'type' => 'varchar',
            'length' => 32,
            'not null' => true
        )
    );

}
