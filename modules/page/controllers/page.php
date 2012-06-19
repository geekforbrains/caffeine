<?php

class Page_PageController extends Controller {

    /**
     * Loads a page object into the current view, if the page exists. Otherwise 404 error is returned.
     *
     * Route: page/:slug
     *
     * @param string $slug The slug of the page to get.
     */
    public static function view($slug)
    {
        if($page = Page::page()->find(Input::clean($slug)))
        {
            View::setTitle($page->title);
            View::data('page', $page);
            return;
        }

        return 404;
    }

}
