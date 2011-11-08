<?php

class Page_PageController extends Controller {

    public static function view($slug)
    {
        $page = Page::page()->find($slug);

        if($page)
        {
            View::data('page', $page);
            return;
        }

        return ERROR_NOTFOUND;
    }

}
