<?php

class User_LineItemModel extends Model {

    public $_belongsTo = array('user.user', 'quote.lineitem', 'quote.lineitemdetail');

    public $_fields = array(
        'num_uses' => array(
            'type' => 'int',
            'not null' => true
        )
    );

}
