<?php
class Blog_CommentModel extends Model {

    public $tableBelongsTo = array('blog.post');

    public $tableTimestamps = true;

    public $tableFields = array(
        'name' => array(
            'type' => 'varchar',
            'length' => 255,
            'not null' => true
        ),
        'email' => array(
            'type' => 'varchar',
            'length' => 255,
            'not null' => true
        ),
        'body' => array(
            'type' => 'text',
            'size' => 'normal',
            'not null' => true
        )
    );

}
