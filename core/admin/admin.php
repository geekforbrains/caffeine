<?php

class Admin {

    private static $_inAdmin = false;

    public static function inAdmin() {
        return self::$_inAdmin;
    }

    public static function setInAdmin($boolean) {
        self::$_inAdmin = $boolean;
    }

}
