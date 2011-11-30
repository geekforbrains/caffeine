<?php

class Page_Page_AdminController extends Controller {

    public static function manage()
    {
        $rows = array();
        $headers = array(
            array(
                'Title',
                'attributes' => array('colspan' => 2)
            )
        );

        $pages = Page::page()->orderBy('title')->all();

        if($pages)
        {
            foreach($pages as $page)
            {
                $rows[] = array(
                    Html::a()->get($page->title, 'admin/page/edit/' . $page->id),
                    array(
                        Html::a()->get('Delete', 'admin/page/delete/' . $page->id),
                        'attributes' => array('align' => 'right')
                    )
                );
            }
        }
        else
        {
            $rows[] = array(
                array(
                    '<em>No pages.</em>',
                    array(
                        'attributes' => array('colspan' => 2)
                    )
                )
            );
        }

        return Html::table()->build($headers, $rows);
    }

    public static function create()
    {
        if($_POST)
        {
            if(Html::form()->validate())
            {
                $pageId = Page::page()->insert(array(
                    'page_id' => $_POST['page_id'],
                    'user_id' => User::current()->id,
                    'slug' => String::slugify($_POST['title']),
                    'title' => $_POST['title'],
                    'body' => $_POST['body']
                ));

                if($pageId)
                    Message::ok('Page created successfully.');
                else
                    Message::error('Error creating page. Please try again.');
            }
        }

        $fields = array(
            'page_id' => array(
                'title' => 'Parent',
                'type' => 'select',
                'options' => array('-')
            ),
            'title' => array(
                'title' => 'Title',
                'type' => 'text',
                'validate' => array('required')
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

        return Html::form()->build($fields);
    } 

    public static function edit($id)
    {
        $page = Page::page()->find($id);

        $fields = array(
            'page_id' => array(
                'title' => 'Parent',
                'type' => 'select',
                'options' => array('-')
            ),
            'title' => array(
                'title' => 'Title',
                'type' => 'text',
                'default_value' => $page->title,
                'validate' => array('required')
            ),
            'slug' => array(
                'title' => 'Slug',
                'type' => 'text',
                'default_value' => $page->slug,
                'validate' => array('required')
            ),
            'body' => array(
                'title' => 'Body',
                'type' => 'textarea',
                'class' => 'medium textarea',
                'attributes' => array(
                    'class' => 'tinymce'
                ),
                'default_value' => $page->body
            ),
            'submit' => array(
                'value' => 'Create Page',
                'type' => 'submit'
            )
        );

        return Html::form()->build($fields);
    }

    public static function delete($id)
    {

    }

}
