<?php

class Blog_CategoryModel extends Model {

    public $tableHasAndBelongsToMany = array('blog.post');

    public $tableFields = array(
        'name' => array(
            'type' => 'varchar',
            'length' => 255,
            'not null' => true
        )
    );

}
