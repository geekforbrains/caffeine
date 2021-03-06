<?php

class Page_Admin_PageController extends Controller {

    /**
     * Displays a table of pages, either for all users or the currently logged in users pages only 
     * based on permissions.
     *
     * Route: admin/pages/manage
     */
    public static function manage()
    {
        // Check if user only has access to their pages
        if(!User::current()->hasPermission('page.manage'))
            $pages = Page::page()->where('user_id', '=', User::current()->id)->orderBy('title')->all();
        else
            $pages = Page::page()->orderBy('title')->all();

        $table = Html::table();
        $header = $table->addHeader();
        $header->addCol('Title');
        $header->addCol('User', array('colspan' => 2));

        if($pages)
        {

            MultiArray::load($pages, 'page_id');
            $indentedPages = MultiArray::indent();

            foreach($indentedPages as $page)
            {
                $user = User::user()->find($page->user_id);

                $row = $table->addRow();
                $row->addCol(Html::a()->get($page->indent . $page->title, 'admin/page/edit/' . $page->id));
                $row->addCol($user->email);
                $row->addCol(
                    Html::a('Delete', 'admin/page/delete/' . $page->id, array(
                        'onclick' => "return confirm('Delete this page? All child pages will be deleted as well.')"
                    )),
                    array(
                        'class' => 'right'
                    )
                );
            }
        }
        else
            $table->addRow()->addCol('<em>No pages</em>', array('colspan' => 2));

        return array(
            'title' => 'Manage Page',
            'content' => $table->render()
        );
    }

    /**
     * Displays a create page form.
     *
     * Route: admin/pages/create
     */
    public static function create()
    {
        if(isset($_POST['create_page']) && Html::form()->validate())
        {
            $pageId = Page::page()->insert(array(
                'page_id' => $_POST['page_id'],
                'user_id' => User::current()->id,
                'slug' => String::slugify($_POST['title']),
                'title' => $_POST['title'],
                'body' => $_POST['body']
            ));

            if($pageId)
            {
                Message::ok('Page created successfully.');
                $_POST = array(); // Clear form
            }
            else
                Message::error('Error creating page. Please try again.');
        }

        // Either get all pages or only current users pages based on permission
        if(!User::current()->hasPermission('page.manage'))
            $pages = Page::page()->where('user_id', '=', User::current()->id)->all();
        else
            $pages = Page::page()->all();

        // Indent pages based on their parent for a tree-style display in dropdown menu
        MultiArray::load($pages, 'page_id');
        $indentedPages = MultiArray::indent();
        $arrPages = array(0 => '-');

        // Form build required array for select options, create options from indented pages
        foreach($indentedPages as $page)
            $arrPages[$page->id] = $page->indent . $page->title;

        $fields[] = array(
            'fields' => array(
                'page_id' => array(
                    'title' => 'Parent',
                    'type' => 'select',
                    'options' => $arrPages
                ),
                'title' => array(
                    'title' => 'Title',
                    'type' => 'text',
                    'validate' => array('required')
                ),
                'body' => array(
                    'title' => 'Body',
                    'type' => 'textarea',
                    'attributes' => array('class' => 'tinymce')
                ),
                'create_page' => array(
                    'value' => 'Create Page',
                    'type' => 'submit',
                )
            )
        );

        return array(
            array(
                'title' => 'Create Page',
                'content' => Html::form()->build($fields)
            )
        );
    } 


    /**
     * Displays an edit page form.
     *
     * Route: admin/pages/edit/:num
     *
     * @param int $id The id of the page to edit
     */
    public static function edit($id)
    {
        if(isset($_POST['update_page']) && Html::form()->validate())
        {
            $status = Page::page()->where('id', '=', $id)->update(array(
                'page_id' => $_POST['page_id'],
                'title' => $_POST['title'],
                'slug' => $_POST['slug'],
                'body' => $_POST['body']
            ));

            if($status)
                Message::ok('Page updated successfully.');
            else
                Message::error('Error updating page. Please try again.');
        }

        $page = Page::page()->find($id);

        // Either get all pages or only current users pages based on permission
        if(!User::current()->hasPermission('page.manage'))
            $pages = Page::page()
                ->where('user_id', '=', User::current())
                ->andWhere('id', '!=', $id)
                ->all();
        else
            $pages = Page::page()->where('id', '!=', $id)->all();

        MultiArray::load($pages, 'page_id');
        $indentedPages = MultiArray::indent();
        $arrPages = array(0 => '-');

        foreach($indentedPages as $p)
            $arrPages[$p->id] = $p->indent . $p->title;

        $fields[] = array(
            'fields' => array(
                'page_id' => array(
                    'title' => 'Parent',
                    'type' => 'select',
                    'options' => $arrPages,
                    'selected' => $page->page_id
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
                    'default_value' => $page->body,
                    'attributes' => array('class' => 'tinymce')
                ),
                'update_page' => array(
                    'value' => 'Update Page',
                    'type' => 'submit',
                )
            )
        );

        return array(
            array(
                'title' => 'Edit Page',
                'content' => Html::form()->build($fields)
            )
        );
    }

    /**
     * Deletes a page based on its and and redirect to admin/pages/manage.
     *
     * @param int $id The id of the page to delete.
     */
    public static function delete($id)
    {
        self::_deleteRelated($id); 
        Message::ok('Page deleted successfully.');
        Url::redirect('admin/page');
    }


    /**
     * Deletes any pages related to (are children of) the given page $id.
     *
     * @param int $id The id of the page to delete any child pages for.
     */
    private static function _deleteRelated($id)
    {
        $relatedPages = Page::page()->where('page_id', '=', $id)->all();

        if($relatedPages)
            foreach($relatedPages as $p)
                self::_deleteRelated($p->id);

        Page::page()->delete($id);
    }


}
