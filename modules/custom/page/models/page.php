<?php

class Page_PageModel extends Model { 

    public $tableTimestamps = true;

    public $tableFields = array(
        'slug' => array(
            'type' => 'varchar',
            'length' => 255,
            'not null' => true
        ),
        'title' => array(
            'type' => 'varchar',
            'length' => 255,
            'not null' => true
        ),
        'body' => array(
            'type' => 'text',
            'size' => 'normal',
            'not null' => true
        ),
    );

    public $tableIndexes = array('slug');

    public $tableFulltext = array('title', 'body');

}
