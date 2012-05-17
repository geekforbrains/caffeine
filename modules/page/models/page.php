<?php

class Page_PageModel extends Model { 

    public $_timestamps = true;

    public $_belongsTo = array('user.user', 'page.page');

    public $_fields = array(
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

    public $_indexes = array('slug');

    public $_fulltext = array('title', 'body');

    public function getParent() {
        return $this->where('page_id', '=', $this->page_id)->first();
    }

}
