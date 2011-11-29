<?php

class User_Current extends Module {

    /**
     * Stores the current user singleton instance.
     */
    private static $_instance = null;

    /**
     * Stores the current users details. Defaults to an anonymous user if no one is
     * logged in.
     */
    private static $_user = array(
        'id' => 0,
        'email' => null,
        'permissions' => array(),
        'is_admin' => false
    );

    /**
     * Force class as singleton
     */
    private function __construct() {}
    public function __clone() {}
    public function __wakeup() {}

    /**
     * Getter method is used for getting current user properties within self::$_user.
     *
     * Ex: User::current()->id;
     */
    public function __get($name)
    {
        if(isset(self::$_user[$name]))
            return self::$_user[$name];
        return null;
    }

    /**
     * Returns the singleton instance of this class.
     */
    public static function singleton()
    {
        if(is_null(self::$_instance))
        {
            $className = __CLASS__;
            self::$_instance = new $className;

            if(isset($_SESSION[Config::get('user.session_key')]))
            {
                $user = User::user()->where('id', '=', $_SESSION[Config::get('user.session_key')])->first();

                if($user)
                {
                    self::$_user['id'] = $user->id;
                    self::$_user['email'] = $user->email;
                    self::$_user['is_admin'] = $user->is_admin;

                    $permissions = User::permission()
                        ->leftJoin('roles_users', 'roles_users.role_id', '=', 'user_permissions.role_id')
                        ->where('roles_users.user_id', '=', $user->id)
                        ->distinct()
                        ->select('user_permissions.permission')
                        ->get();

                    if($permissions)
                    {
                        foreach($permissions as $row)
                            self::$_user['permissions'][] = $row->permission;
                    }
                }
            }
        }

        return self::$_instance;
    }

    /**
     * Determines if a user is logged in (anonymous) or not. A user with an ID of 0 is always
     * anonymous and not logged in.
     */
    public static function isAnonymous()
    {
        if(self::$_user['id'] > 0)
            return false;
        return true;
    }

    /**
     * Checks of the current user has the given permission.
     *
     * Ex: User::current()->hasPermission('user.manage');
     */
    public function hasPermission($permission)
    {
        if(self::$_user['is_admin'] > 0)
        {
            Dev::debug('user', 'User is admin, ignore permissions.');
            return true;
        }

        if(is_array($permission))
        {
            foreach($permission as $p)
            {
                if($this->hasPermission($p))
                    return true;
            }
        }

        elseif(in_array($permission, self::$_user['permissions']))
            return true;

        return false;
    }

}
