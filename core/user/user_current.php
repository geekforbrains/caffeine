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
    private static $_user = null;

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
        if(isset(self::$_user->{$name}))
            return self::$_user->{$name};

        return null;
    }

    /**
     * Gets the value of the given data name for the current user. Returns null if the data
     * doesn't exist.
     */
    public function getData($name)
    {
        $data = User::data()->where('user_id', '=', self::$_user->id)
            ->andWhere('name', '=', $name)
            ->first();

        if($data)
            return $data->value;
        return null;
    }

    /**
     * Returns all additional data set for the current user.
     */
    public function getAllData() {
        return User::data()->where('user_id', '=', self::$_user->id);
    }

    /**
     * Set additional data for the user account. This is used for storing additional content
     * that isn't supported by the default user table.
     */
    public function setData($name, $value)
    {
        if(self::$_user->id == 0)
            return false;

        if(User::data()->where('user_id', '=', self::$_user->id)->andWhere('name', '=', $name)->first())
        {
            return User::data()->where('user_id', '=', self::$_user->id)
                ->andWhere('name', '=', $name)
                ->update(array(
                    'value' => $value
                ));
        }
        else
        {
            return User::data()->insert(array(
                'user_id' => self::$_user->id,
                'name' => $name,
                'value' => $value
            ));
        }
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
                    self::$_user = $user;
                    self::$_user->permissions = array();

                    $permissions = User::permission()
                        ->select('user_permissions.permission')->distinct()
                        ->leftJoin('habtm_userroles_userusers t', 't.user_role_id', '=', 'user_permissions.role_id')
                        ->where('t.user_user_id', '=', $user->id)
                        ->get();

                    if($permissions)
                    {
                        foreach($permissions as $row)
                            self::$_user->permissions[] = $row->permission;
                    }
                }
            }

            if(is_null(self::$_user))
            {
                self::$_user = User::user()->blank(); // Create empty user
                self::$_user->permissions = array();
            }

            Log::debug('user', 'Current user ID: ' . self::$_user->id);
        }

        return self::$_instance;
    }

    /**
     * Determines if a user is logged in (anonymous) or not. A user with an ID of 0 is always
     * anonymous and not logged in.
     */
    public static function isAnonymous()
    {
        if(self::$_user->id > 0)
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
        if(self::$_user->is_admin > 0)
            return true;

        if(is_array($permission))
        {
            foreach($permission as $p)
            {
                if($this->hasPermission($p))
                    return true;
            }
        }

        elseif(in_array($permission, self::$_user->permissions))
            return true;

        return false;
    }

}
