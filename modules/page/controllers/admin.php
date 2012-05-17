<?php

class Page_AdminController extends Controller {

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
        $header->addCol('Title', array('colspan' => 2));

        if($pages)
        {

            MultiArray::load($pages, 'page_id');
            $indentedPages = MultiArray::indent();

            foreach($indentedPages as $page)
            {
                $user = User::user()->find($page->user_id);

                $row = $table->addRow();
                $row->addCol(Html::a()->get($page->indent . $page->title, 'admin/page/edit/' . $page->id));
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
            'title' => 'Manage Pages',
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
        if(Input::post('create_page') && Html::form()->validate())
        {
            $post = Input::clean($_POST);

            $pageId = Page::page()->insert(array(
                'page_id' => $post['page_id'],
                'user_id' => User::current()->id,
                'slug' => String::slugify($post['title']),
                'title' => $post['title'],
                'body' => $post['body']
            ));

            if($pageId)
            {
                Message::ok('Page created successfully.');
                Url::redirect('admin/page/manage');
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


        $form = Html::form()->addFieldset();

        $form->addSelect('page_id', array(
            'title' => 'Parent',
            'options' => $arrPages
        ));

        $form->addText('title', array(
            'title' => 'Title',
            'validate' => array('required')
        ));

        $form->addTextarea('body', array(
            'title' => 'Body',
            'attributes' => array(
                'class' => 'span6',
                'rows' => 8
            )
        )); 

        $form->addSubmit('create_page', 'Create Page');
        $form->addLink(Url::to('admin/page/manage'), 'Cancel');

        return array(
            array(
                'title' => 'Create Page',
                'content' => $form->render()
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
        if(Input::post('update_page') && Html::form()->validate())
        {
            $post = Input::clean($_POST);

            $status = Page::page()->where('id', '=', $id)->update(array(
                'page_id' => $post['page_id'],
                'title' => $post['title'],
                'slug' => $post['slug'],
                'body' => $post['body']
            ));

            if($status)
            {
                Message::ok('Page updated successfully.');
                Url::redirect('admin/page/manage');
            }
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

        $form = Html::form()->addFieldset();

        $form->addSelect('page_id', array(
            'title' => 'Parent',
            'options' => $arrPages,
            'selected' => $page->page_id
        ));

        $form->addText('title', array(
            'title' => 'Title',
            'value' => $page->title,
            'validate' => array('required')
        ));

        $form->addText('slug', array(
            'title' => 'Slug',
            'value' => $page->slug,
            'validate' => array('required')
        ));

        $form->addTextarea('body', array(
            'title' => 'Body',
            'value' => $page->body,
            'attributes' => array(
                'class' => 'span6',
                'rows' => 8
            )
        ));

        $form->addSubmit('update_page', 'Update Page');
        $form->addLink(Url::to('admin/page/manage'), 'Cancel');

        return array(
            array(
                'title' => 'Edit Page',
                'content' => $form->render()
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
        Url::redirect('admin/page/manage');
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
