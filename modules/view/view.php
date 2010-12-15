<?php
/**
 * =============================================================================
 * View
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * 
 * @event block_paths
 *      This event is used to register block paths. Block paths are associated
 *      with a class name. The returned data should be an array where the key
 *      is the class name and the value is the full path to the blocks
 *      directory. The path should have a trailing slash. Multiple classes and
 *      paths can be returned.
 *
 *      Example:
 *          return array('MyClass' => '/path/to/myclass/blocks/');
 *          return array(
 *              'MyClass1' => '/path/to/myclass1/blocks/',
 *              'MyClass2' => '/path/to/myclass2/blocks/'
 *          );
 *
 * =============================================================================
 */
class View {
    
	// The current theme name
	protected static $_theme				= null;

	// The theme directory path
	protected static $_theme_dir			= null;

    // The full theme path
    protected static $_theme_path			= null;
    
    // The path to the blocks directory in the current theme
    protected static $_theme_blocks_path    = null;

	// Stores theme metadata such as css and js files
	protected static $_theme_meta			= array('css' => array(), 'js' => array());
    
    // Paths to blocks, associated by class name
    protected static $_block_paths          = array();
    
    // Module block paths, set via the View::block_paths event
    protected static $_loaded_block			= array();

	// Should we echo loaded blocks or not
	protected static $_output_blocks		= false;
    
	/**
	 * -------------------------------------------------------------------------
	 * Callback for the View::change_theme event.
	 * -------------------------------------------------------------------------
	 */
	public static function callback_change_theme($class, $data) 
	{
		if(isset($data['theme']))
			self::theme($data['theme']);	
	}

    /**
     * -------------------------------------------------------------------------
     * Callback for the View::block_paths event.
     * -------------------------------------------------------------------------
     */
    public static function callback_block_paths($class, $data) 
    {
        foreach($data as $c => $path)
        {
            Caffeine::debug(2, 'View', 'Class "%s" setting block path to %s', 
                $c, $path);
            self::$_block_paths[strtolower($c)] = $path;
        }
    }
    
    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
	public static function add_css($media, $css) 
	{
		self::$_theme_meta['css'][] = array(
			'media' => $media,
			'css' => $css
		);
	}

    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
	public static function get_css() 
	{
		$html = '<link rel="stylesheet" type="text/css" href="%s" media="%s" />';

		foreach(self::$_theme_meta['css'] as $css)
			echo sprintf($html, self::theme_url($css['css']), $css['media']);
	}

    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
	public static function add_js($js) {
		self::$_theme_meta['js'][] = $js;
	}

    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
	public static function get_js()
	{
		$html = '<script type="text/javascrip" src="%s"></script>';

		foreach(self::$_theme_meta['js'] as $js)
			echo sprintf($html, self::theme_url($js));
	}

    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
	public static function theme_url($path) {
		return Router::url(self::$_theme_dir . $path);
	}

	/**
	 * -------------------------------------------------------------------------
	 * Checks if a theme exists either in the current site directory or in the
	 * root view directory.
	 *
	 * @param $theme
	 *		The name of the theme to search for.
	 *
	 * @return
	 * 		Returns the theme path if one is found, otherwise boolean false.
	 * -------------------------------------------------------------------------
	 */
	public static function theme_exists($theme)
	{
		$theme_path = false;

		// If a site directory exists, first check if the theme exists there
		if(!is_null(Caffeine::get_site_path()))
		{
			$site_path = CAFFEINE_SITES_PATH .
				Caffeine::get_site() . '/' .
				VIEW_DIR . $theme . '/';

			Caffeine::debug(2, 'View', 'Checking site theme path: %s', $site_path);

			if(file_exists($site_path))
				$theme_path = $site_path;
		}

		// Either no site exists, or the site doesn't contain the theme, try
		// root views directory
		$view_path = VIEW_PATH . $theme . '/';
		Caffeine::debug(2, 'View', 'Checking root theme path: %s', $view_path);

		if(!$theme_path && file_exists($view_path))
			$theme_path = $view_path;

		return $theme_path;
	}

    /**
     * -------------------------------------------------------------------------
     * Sets the theme view directory that all view and block override files will
     * be loaded from.
	 *
	 * If a site directory exists, we first check if the theme exists within
	 * it. Otherwise load the theme from the root views directory.
     *
     * @param $theme
     *      The theme directory name to use, located in the views directory.
     * -------------------------------------------------------------------------
     */
    public static function theme($theme = VIEW_DEFAULT_THEME) 
    {
		$theme_path = self::theme_exists($theme);

		if($theme_path)
		{
			Caffeine::debug(1, 'View', 'Setting theme path to: %s', $theme_path);
			self::$_theme = $theme;
			self::$_theme_path = $theme_path;
			self::$_theme_dir = str_replace(CAFFEINE_ROOT, '', self::$_theme_path);
			self::$_theme_blocks_path = $theme_path . VIEW_BLOCKS_DIR;
			self::_load_theme_functions();
		}
		else
			trigger_error('Theme does\'t exist: ' . $theme, E_USER_ERROR);
    }

    /**
     * -------------------------------------------------------------------------
     * Used to load a block file, inject any view data and return the
     * generated HTML.
     *
     * When a block is loaded, it first checks if the block file exists in the
     * themes "blocks" directory. This allows you to override block HTML. If an
     * override file doesn't exist, the block HTML is loaded from the default
     * path specified by the class loading the block.
     *
     * @param $class
     *      The class the block is associated to. This is used to determine the
     *      path, which is set during the view_block_paths event().
     *
     * @param $block
     *      The name of the block file to load, excluding .php file extension.
     *      This name can include paths, such as "sub-dir/my_block".
     *
     * @param $data
     *      An optional parameter for specifying data to be loaded into the
     *      block. The data is translated into variables and made available to
     *      The blocks HTML. The array key is the variable name, and the array
     *      value is the value associated with that key.
     *
     *      Example:
     *          array('name' => 'John Doe', 'age' => 25);
     *
     *      The above would create the variables $name and $age with the values 
     *      "John Doe" and "25" respectively.
     * -------------------------------------------------------------------------
     */
    public static function load($class, $block, $data = array())
    {
        if(self::$_output_blocks)
        	echo self::_render_block($class, $block, $data);
        else
            self::$_loaded_block = array(
                'class' => $class,
                'block' => $block,
                'data' => $data
            );
    }
    
    /**
     * -------------------------------------------------------------------------
     * Used for loading a file from its path, providing variable data and 
	 * returning the generated HTML.
     *
     * @param $path
     *      The full file path to be loaded.
     *
     * @param $data
     *      An optional array of view data to be made available to the loaded
     *      HTML. See View::load() for more details.
     *
     * @return
     *      Returns a string of generated HTML.
     * -------------------------------------------------------------------------
     */
    public static function render($path, $data = array())
    {
        if($data)
            foreach($data as $k => $v)
                $$k = $v;
        
        ob_start();
        eval('?>' .file_get_contents($path). '<?');
        $buffer = ob_get_contents();
        ob_end_clean();
        
        return $buffer;
    }
    
    /**
     * -------------------------------------------------------------------------
     * Used to include a view file. Normally this method is called within other
     * views. Supports loading views within subdirectories.
     *
     * Example:
     *      View::insert('header');
     *      View::insert('includes/header');
     *
     * @param $view_file
     *      The name of the view to insert, without the .php file extension.
     * -------------------------------------------------------------------------
     */
    public static function insert($view_file)
    {
        Caffeine::debug(1, 'View', 'Inserting view "%s"', $view_file);

        $view_file = self::$_theme_path . $view_file . CAFFEINE_EXT;
        if(file_exists($view_file))
            require_once($view_file);
    }

    /**
     * -------------------------------------------------------------------------
     * Sets any additional blocks that are loaded to be output to the buffer
	 * directly. This allows views and blocks to have embedded method calls.
	 *
	 * The view is then determined, loaded and output to the browser.
	 *
	 * TODO: View caching
     * -------------------------------------------------------------------------
     */
    public static function output()
    {           
		self::$_output_blocks = true;
		$view = self::_render_view();
		
		echo $view;
    }

	/**
	 * ------------------------------------------------------------------------
	 * Used to get the main loaded content for a view.
	 * ------------------------------------------------------------------------
	 */
	public static function content()
	{
		echo self::_render_block(
			self::$_loaded_block['class'],
			self::$_loaded_block['block'],
			self::$_loaded_block['data']
		);
	}
    
    /**
     * -------------------------------------------------------------------------
     * Renders the main theme view. This method goes through several steps when
	 * loading the view, providing the developer the ability to override
	 * specific view output.
	 *
	 * The method looks for the following views in order:
	 *		- A view with the same name as the main block being loaded.
	 *		- A view with the same name as the module loading the block.
	 *		- A view with the default name (set in view_config.php)
	 *
	 * When loading a block as a view, the blocks data (variables) will be
	 * available within the view. This way you can manually re-create the blocks
	 * contents, or simple re-style the page and re-call the blocks callback
	 * manually.
     * -------------------------------------------------------------------------
     */
    private static function _render_view()
    {
        $view_path = self::$_theme_path . VIEW_INDEX . CAFFEINE_EXT;
        $view_data = array();
        $view_key = null;
        
		// Check for view with same name as block
		$block_path = self::$_theme_path . self::$_loaded_block['block'] . 
			CAFFEINE_EXT;

		Caffeine::debug(2, 'View', 'Checking for block view: %s', $block_path);

		if(file_exists($block_path))
		{
			$view_path = $block_path;
			$view_data = self::$_loaded_block['data'];
		}
        
		// Check for view with same name as module loading this block
		$module_path = Caffeine::get_class_module(self::$_loaded_block['class']);

		if($module_path)
		{
			$module_path = self::$_theme_path . $module_path . CAFFEINE_EXT;
			Caffeine::debug(2, 'View', 'Checking for module view: %s', $module_path);

			if(file_exists($module_path))
				$view_path = $module_path;
		}
            
        Caffeine::debug(1, 'View', 'Rendering view: %s', $view_path);
        return self::render($view_path, $view_data);
    }
    
    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
    private static function _render_block($class, $block, $data = array())
    {
        $class = strtolower($class);
        $path = null;
            
        $override_block = self::$_theme_blocks_path . $block . CAFFEINE_EXT;
		Caffeine::debug(3, 'View', 'Checking for block path: %s', $override_block);

        if(file_exists($override_block))
            $path = $override_block;
               
        elseif(isset(self::$_block_paths[$class]))
        {
            $orig_block = self::$_block_paths[$class] . $block . CAFFEINE_EXT;
            if(file_exists($orig_block))
                $path = $orig_block;
        }
            
        if(is_null($path))
            trigger_error('Block doesn\'t exist: ' . $class . ' - ' . $block, E_USER_ERROR);
            
        Caffeine::debug(2, 'View', 'Rendering block: %s', $path);
        return self::render($path, $data);
    }

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	private static function _load_theme_functions()
	{
		$path = self::$_theme_path . VIEW_FUNCTION_FILE . CAFFEINE_EXT;

		if(file_exists($path))
			require_once($path);
	}

}
