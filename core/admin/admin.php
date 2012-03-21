<?php

class Admin {

    private static $_inAdmin = false;

    public static function inAdmin() {
        return self::$_inAdmin;
    }

    public static function setInAdmin($boolean) {
        self::$_inAdmin = $boolean;
    }

    /**
     * Determines if admin has been installed by checking if an admin
     * user has been created.
     */
    public static function isConfigured()
    {
        if(User::user()->where('is_admin', '>', 0)->limit(1)->first())
            return true;
        return false;
    }

}
