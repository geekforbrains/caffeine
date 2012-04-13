<?php

class Page_Api {

    public static function getPages($data)
    {
        $pages = Page::page()->all();

        return array(
            'code' => 200,
            'data' => $pages ? Model::toArray($pages) : array()
        );
    }

    public static function getPage($data, $id = 0)
    {
        if($page = Page::page()->find($id))
        {
            return array(
                'code' => 200,
                'data' => Model::toArray($page)
            );
        }

        return array(
            'code' => 404,
            'message' => 'Page doesn\'t exist.'
        );
    }

    public static function createPage($data)
    {
        return array(
            'code' => 200,
            'data' => $data
        );
    }

}
