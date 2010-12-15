<?php
final class Message_Events extends Message {

    public static function caffeine_bootstrap() {
        self::_move_stored();
    }

    public static function view_block_paths() {
        return array('Message' => CAFFEINE_MODULES_PATH . 'message/blocks/');
    }
    
    public static function view_block_callbacks() {
        return array('messages' => array('Message', 'display_messages'));
    }

}
