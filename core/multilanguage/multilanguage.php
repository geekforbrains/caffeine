<?php

class Multilanguage extends Module {

    private static $_registeredModules = null;
    private static $_moduleContent = null;
    private static $_contentType = null;
    private static $_currentLang = null; // If no language is set, default is null

    /**
     * Gets the current language. If non is set, this will return null. If there is a language
     * set, the language object will be returned which contains the language id, code and human readable name
     *
     * @return Language object if one is set, null otherwise
     */
    public static function getCurrentLang() {
        return self::$_currentLang;
    }

    /**
     * Used to convert an object to its translated version if a langauge is set and the object is supported.
     * If no langauge is set, the $data is not an object or the object isn't supported (not implemented by the module that created it)
     * it'll be returned without modification.
     */
    public static function getTranslation($data)
    {
        if(is_array($data))
        {
            foreach($data as $k => $v)
                $data[$k] = self::getTranslation($v);
        }
        elseif(self::$_currentLang && is_object($data) && isset($data->id)) // Objects (models) with id's are required
        {
            $bits = explode('_', strtolower(get_class($data))); // Get module and content type from model name (ex: Module_TypeModel)
            $module = $bits[0];
            $type = str_replace('model', '', $bits[1]);

            Dev::debug('multilanguage', sprintf('Translating view data %s:%s', $module, $type));

            $content = Multilanguage::content()
                ->where('module', '=', $module)
                ->andWhere('type', '=', $type)
                ->andWhere('type_id', '=', $data->id)
                ->andWhere('language_id', '=', self::$_currentLang->id)
                ->first();

            // If a conversion for the current content type has been found for the current language
            if($content)
            {
                // Get text items, if any
                if($textItems = Multilanguage::text()->where('content_id', '=', $content->id)->all());
                    foreach($textItems as $i)
                        $data->{$i->name} = $i->content; // Set property to new translated version

                // Get textarea items, if any
                if($textareaItems = Multilanguage::textarea()->where('content_id', '=', $content->id)->all());
                    foreach($textareaItems as $i)
                        $data->{$i->name} = $i->content; // Set property to new translated version

                // Get files, if any
                // TODO
            }
        }

        return $data;
    }

    /**
     * Checks if an available language code is set in the current route.
     *
     * @param string $currentRoute The current route to check for a language code
     */
    public static function urlHasLangCode($currentRoute)
    {
        $code = null;

        if(strstr($currentRoute, '/'))
        {
            $bits = explode('/', $currentRoute);
            if($bits && strlen($bits[0]) == 3)
                $code = $bits[0];
        }
        elseif(strlen($currentRoute) == 3)
            $code = $currentRoute;

        if(!is_null($code))
        {
            $lang = Multilanguage::language()->where('code', 'LIKE', $code)->first();
            
            if($lang)
            {
                Dev::debug('multilanguage', 'Setting language to: ' . $lang->name);
                self::$_currentLang = $lang;
                return true;
            }
        }
         
        return false;
    }

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
