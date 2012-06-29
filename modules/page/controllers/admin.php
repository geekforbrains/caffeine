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
