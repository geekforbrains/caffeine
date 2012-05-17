<?php

class User extends Module {

    /**
     * Stores permissions loaded from module setup.php files.
     */
    private static $_permissions = array();

    /**
     * Stores an array of permissions sorted by module.
     */
    private static $_sortedPermissions = null;

    /**
     * Stores the status of the current permission event. This works with
     * the User::permissionCallback() event to store negative responses.
     */
    private static $_permissionStatus = true;

    /**
     * Returns the array of permissions loaded from module setup.php files.
     */
    public static function getPermissions() {
        return self::$_permissions;
    }

    /**
     * Sorts permissions loaded from module setup.php files into a 2d array.
     * The first array key is the name of the module, the 2nd array is the
     * permissions created in the setup.php file where the key is the permission
     * and the value is the description.
     *
     * @return array Sorted array of module permissions.
     */
    public static function getSortedPermissions()
    {
        if(is_null(self::$_sortedPermissions))
        {
            foreach(self::$_permissions as $permission => $desc)
            {
                $bits = explode('.', $permission);
                
                if(!isset(self::$_sortedPermissions[$bits[0]]))
                    self::$_sortedPermissions[$bits[0]] = array();

                self::$_sortedPermissions[$bits[0]][$permission] = $desc;
            }

            ksort(self::$_sortedPermissions);
        }

        return self::$_sortedPermissions;
    }

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

    /**
     * Singleton method for getting the instance of the current user.
     */
    public static function current() {
        return User_Current::singleton();
    }

    /**
     * Sends a reset password link to a users email address. This is usually called
     * due to the reset password form being submitted, but may be called directly.
     */
    public static function sendResetPasswordEmail($user)
    {
        $token = String::random(); // Used for verifying user link against $user id and token

        $status = User::user()->where('id', '=', $user->id)->update(array(
            'reset_token' => $token
        ));

        if($status)
        {
            $url = Url::to('admin/set-password/' . $user->id . '/' . $token, true); // true = send as full url

            $template = file_get_contents(Load::asset('user', 'reset_password.txt', false));
            $template = str_replace(':url', $url, $template);
            $template = str_replace(':version', VERSION, $template);

            $mail = Plugin::load('phpmailer');
            $mail->SetFrom(Config::get('system.email_address'), Config::get('system.email_name'));
            $mail->AddAddress($user->email);
            $mail->Subject = 'Reset Password';
            $mail->Body = $template;

            if(!$mail->Send())
            {
                Log::error('user', 'Error sending reset password email: ' . $mail->ErrorInfo);
                return false;
            }

            return true;
        }

        Log::error('user', 'Error updating users reset token');
        return false;
    }

}
