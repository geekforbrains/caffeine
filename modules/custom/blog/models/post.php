<?php
class Blog_PostModel extends Model {

    public $tableBelongsTo = array('user.user');
    public $tableHasMany = array('blog.comment');
    public $tableHasAndBelongsToMany = array('blog.category');

    public $tableTimestamps = true;

    public $tableFields = array(
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
        'slug' => array(
            'type' => 'varchar',
            'length' => 255,
            'not null' => true
        )
    );

    public $tableIndexes = array('slug');
    
    public $tableFulltext = array('title', 'body');

}
