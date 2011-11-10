<?php

class Message extends Module {

    public static function set($type, $msg)
    {
        $message = Message::message();
        $message->type = $type;
        $message->message = $msg;
        $message->save();
    }

    public static function get()
    {
        $messages = array();

        if($msgs = Message::message()->all())
        {
            Message::message()->delete();
            foreach($msgs as $msg)
                $messages[$msg->type][] = $msg->message;
        }

        return $messages;
    }

}
