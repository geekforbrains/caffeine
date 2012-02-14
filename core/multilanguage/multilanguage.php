<?php

class Multilanguage extends Module {

    private static $_registeredModules = null;
    private static $_moduleContent = null;
    private static $_contentType = null;

    /**
     * Triggers the multilanguage.modules event, if it hasn't been triggered already and returns
     * an array of module names that have been registered.
     */
    public static function getRegisteredModules()
    {
        if(is_null(self::$_registeredModules))
        {
            self::$_registeredModules = array();
            Event::trigger('multilanguage.modules', null, array('Multilanguage', '_getRegisteredModules'));
        }

        return self::$_registeredModules;
    }

    /**
     * Triggers the multilanguage.content[module] event and returns the array of registerd content returned.
     *
     * The returned array should specify each type of content the module supports with a sub-array of id and content
     * key value pairs.
     *
     * Example array structure:
     *
     * return array(
     *      'content-type' => array(
     *          'id' => 'content'
     *          'id' => 'content'
     *      )
     * );
     *
     * Example of returned content in the blog module:
     *
     * return array(
     *      'post' => array(
     *          '26' => 'My First Post',
     *          '27' => 'My Second Post'
     *      ),
     *      'category' => array(
     *          '3' => 'Some Category',
     *          '4' => 'Another Category'
     *      )
     * );
     */
    public static function getModuleContent($module)
    {
        Event::trigger('multilanguage.content[' . $module . ']', null, array('Multilanguage', '_getModuleContent'));
        return self::$_moduleContent;
    }

    /**
     * Gets the details for a modules content type. This is used to build a form for creating a new version of the content
     * in a different language.
     *
     * The returned array should specify the array key as the database field name and the value as the type of form field
     * to be used when creating the form.
     *
     * Example for blog posts:
     *
     * return array(
     *      'title' => 'text',
     *      'body' => 'textarea'
     * );
     */
    public static function getContentType($module, $type)
    {
        Event::trigger('multilanguage.content_type[' . $module . '][' . $type . ']', null, array('Multilanguage', '_getContentType'));
        return self::$_contentType;
    }

    /**
     * Callback for the multilanguage.modules event.
     */
    public static function _getRegisteredModules($data) {
        self::$_registeredModules[] = ucfirst(strtolower(trim($data)));
    }

    /**
     * Callback for the multilanguage.content[module] event.
     */
    public static function _getModuleContent($data) {
        self::$_moduleContent = $data;
    }

    /**
     * Callback for the multilanguage.content_type[module][type] event
     */
    public static function _getContentType($data) {
        self::$_contentType = $data;
    }

}
