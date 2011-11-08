<?php

class User extends Module {

    /**
     * Stores permissions loaded from module setup.php files.
     */
    private static $_permissions = array();

    /**
     * Stores the status of the current permission event. This works with
     * the User::permissionCallback() event to store negative responses.
     */
    private static $_permissionStatus = true;

    /**
     * Gets the self::$_permissionStatus property.
     */
    public static function getPermissionStatus() {
        return self::$_permissionStatus;
    }

    /**
     * Load permissions from setup.php files into local property.
     */
    public static function load($permissions) {
        self::$_permissions = array_merge($permissions, self::$_permissions);
    }

    /**
     * Callback for the user.permission[permission.name] event.
     */
    public static function permissionCallback($response)
    {
        if($response === false)
            self::$_permissionStatus = false;
    }

}
