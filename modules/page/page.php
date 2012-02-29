<?php

class Page extends Module {

    public static function getByParentId($parentId) {
        return Page::page()->where('page_id', '=', $parentId)->orderBy('title')->all();
    }

}
