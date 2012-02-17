<?php

class Multilanguage_StringModel extends Model {

    public $_belongsTo = array('multilanguage.language', 'multilanguage.stringcontent');

    public $_fields = array(
        'content' => array(
            'type' => 'text',
            'size' => 'normal',
            'not null' => true
        )
    );

}
