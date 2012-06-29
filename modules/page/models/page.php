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
        'is_published' => array(
            'type' => 'int',
            'length' => 1,
            'not null' => true
        )
    );

    public $_indexes = array('slug', 'is_published');

    public $_fulltext = array('title', 'body');

    public function getParent() {
        return $this->where('page_id', '=', $this->page_id)->first();
    }

    /**
     * Get all pages, indented with the given indent string. Pages are indented based on their parent page.
     */
    public function getIndented($indent = '&nbsp;')
    {
        $pages = $this->orderBy('title')->all();

        if($pages)
            return MultiArray::load($pages, 'page_id')->indent();

        return $pages;
    }

}
