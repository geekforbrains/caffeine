<?php

class Media_FileModel extends Model {


    public $_timestamps = true;


    public $_fields = array(
        'name' => array(
            'type' => 'varchar',
            'length' => 255,
            'not null' => true
        ),
        'path' => array(
            'type' => 'varchar',
            'length' => 255,
            'not null' => true
        ),
        'size' => array(
            'type' => 'double',
            'not null' => true
        ),
        'mime_type' => array(
            'type' => 'varchar',
            'length' => 50,
            'not null' => true
        ),
        'file_type' => array( // image, video, file - used for indexing
            'type' => 'varchar',
            'length' => 5,
            'not null' => true
        )
    );


    public $_indexes = array('name', 'file_type');


}
