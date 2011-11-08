<?php

class Page_Page_AdminController extends Controller {

    public static function manage()
    {
        $headers = array(
            array(
                'Title',
                'attributes' => array('colspan' => 2)
            )
        );

        $rows = array(
            array(
                '<a href="#">One</a>', 
                array(
                    '<a href="#">Delete</a>',
                    'attributes' => array('align' => 'right')
                )
            ),
            array(
                '<a href="#">Two</a>', 
                array(
                    '<a href="#">Delete</a>',
                    'attributes' => array('align' => 'right')
                )
            )
        );

        return Html::table()->build($headers, $rows);
    }

    public static function create()
    {
        $field = array(
            'parent_id' => array(
                'title' => 'Parent',
                'type' => 'select',
                'options' => array('-')
            ),
            'title' => array(
                'title' => 'Title',
                'type' => 'text'
            ),
            'body' => array(
                'title' => 'Body',
                'type' => 'textarea',
                'class' => 'medium textarea',
                'attributes' => array(
                    'class' => 'tinymce'
                )
            ),
            'submit' => array(
                'value' => 'Create Page',
                'type' => 'submit'
            )
        );

        return Html::form()->build($field);
    } 

    public static function edit($id)
    {

    }

    public static function delete($id)
    {

    }

}
