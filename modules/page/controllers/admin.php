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
        return array(
            'pages' => Page::page()->getIndented()
        );
    }

    /**
     * Displays a create page form.
     *
     * Route: admin/pages/create
     */
    public static function create()
    {
        if(Input::post('create_page') || Input::post('save_draft'))
        {
            Validate::check('title', array('required'));

            if(Validate::passed())
            {
                $pageId = Page::page()->insert(array(
                    'page_id' => Input::post('page_id'),
                    'title' => Input::post('title'),
                    'slug' => String::slugify(Input::post('title')),
                    'body' => Input::post('body'),
                    'is_published' => Input::post('create_page') ? 1 : 0
                ));

                if($pageId)
                {
                    Message::ok('Page created successfully.'); 
                    Url::redirect('admin/page/manage');
                }
                else
                    Message::error('Error creating page, please try again.');
            }
        }

        return array(
            'pages' => Page::page()->getIndented()
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
        if(!$page = Page::page()->find($id))
            return 404;

        if(Input::post('update_page') || Input::post('save_draft'))
        {
            Validate::check('title', array('required')); 
            Validate::check('slug', array('required'));

            if(Validate::passed())
            {
                if(Input::post('page_id') == $id)
                    Message::error('Invalid parent page, please try again.');
                else
                {
                    $pageId = Page::page()->where('id', '=', $id)->update(array(
                        'page_id' => Input::post('page_id'),
                        'title' => Input::post('title'),
                        'slug' => Input::post('slug'),
                        'body' => Input::post('body'),
                        'is_published' => Input::post('update_page') ? 1 : 0
                    ));

                    if($pageId || $pageId == 0)
                        Message::ok('Page updated successfully.');
                    else
                        Message::error('Error updating page, please try again.');
                }
            }
        }

        return array(
            'page' => $page,
            'pages' => Page::page()->getIndented()
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
