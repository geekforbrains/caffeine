<?php

/**
 * Notes
 *
 * - Methods that start with an underscore cannot be loaded via url
 */
class Blog_PostsController extends Controller {

    /**
     * Specifies permissions for url access of this controllers methods.
     */
    public static function _permissions()
    {
        return array(
            'single' => array('blog.view_posts', 'blog.view_my_posts') // User needs the following permissions to access single method
        );
    }

    public static function _test()
    {
        die('test');
    }

    /**
     * The default method called when one isn't present in the url
     * example.com/module/controller/method
     *
     * This should remain public for if some reason another module wants to call it
     *
     * Because no method was passed, there is no way this method should be able to receive
     * params as well. This is just a catch all for root paths
    public static function _default()
    {
        die('_default');
    }
    */

    // Methods with dashes in the url are re-written in camel case
    // example.com/module/controller/my-method = Controller::myMethod
    public static function myMethod()
    {
        die('_myMethod');
    }

    public static function recent()
    {
        die('recent');
    }

    // Index is loaded by default
    public static function single($slug)
    {
        /*
        $categories = Blog::category()->all();
        $posts = Blog::post()->all();

        foreach($posts as $post)
        {
            $post->categories = $categories;
            $post->user = 23;
            $post->save();
        }
        */

        //Blog::post()->delete(1);

        //User::user()->delete(5);
        die("single($slug)");
    } 

    // Example of manaul permission check
    public static function permission_example()
    {
        if(!User::hasPermission('blog.view_posts', 'blog.view_my_posts'))
            return ERROR_ACCESS_DENIED;
    }

}
