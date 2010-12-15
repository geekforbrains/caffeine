<?php
final class Menu_Events extends Menu {

    public static function view_block_paths() {
        return array('Menu' => CAFFEINE_MODULES_PATH . 'menu/blocks/');
    }

}
