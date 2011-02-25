<?php if(!defined('CAFFEINE_ROOT')) die ('No direct script access allowed.');
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
    //protected static $_block_paths          = array();
    
    // Module block paths, set via the View::block_paths event
    protected static $_loaded_block			= array();

	// Should we echo loaded blocks or not
	protected static $_output_blocks		= false;

	// Should we check for views that match the url segments.
	// This is used to temporarily disable segment checks for loading views
	// directly, such as a 404 page.
	// @see View::load
	protected static $_check_segments		= true;

	// Stores the currently loaded view
	// This is to look for override blocks specific to a view
	// @see View::_render_block
	protected static $_view_name			= VIEW_INDEX;

	// Stores the title last set via the View::set_title method
	protected static $_view_title			= null;

	/**
	 * -------------------------------------------------------------------------
	 * Used for setting the page title. This itself does not set the page title
	 * but provides a title to be used. To show custom titles, the <title> tag
	 * should call the View::get_title method.
	 *
	 * @param $title
	 *		The title to give the current page.
	 * -------------------------------------------------------------------------
	 */
	public static function set_title($title) {
		self::$_view_title = $title;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Used for getting the current page title. If a title has been set, that
	 * title will be returned with any prepended or appended strings set in the
	 * params. If a title has not been set, the $default will be returned.
	 *
	 * @param $default
	 *		The default title to use if no title has been set by the system.
	 *
	 * @param $prepend
	 *		A string to prepend a set title with. This will ONLY be prepended
	 *		to a title set by the system, it will not be prepended to $default.
	 *
	 * @param $append
	 *		A string to append to the set title. This will ONLY append to a
	 *		title set by the system, it will not be appended to $default.
	 *
	 * @return string
	 *		Returns either a set title or the default title as a string.
	 * -------------------------------------------------------------------------
	 */
	public static function get_title($default, $prepend = null, $append = null)
	{
		if(!is_null(self::$_view_title))
		{
			$title = self::$_view_title;
			
			if(!is_null($prepend))
				$title = $prepend . $title;

			if(!is_null($append))
				$title .= $append;

			return $title;
		}

		return $default;
	}
    
    
    /**
     * -------------------------------------------------------------------------
     * DEPRECATED
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
     * DEPRECATED
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
     * DEPRECATED
     * -------------------------------------------------------------------------
     */
	public static function add_js($js) {
		self::$_theme_meta['js'][] = $js;
	}

    /**
     * -------------------------------------------------------------------------
     * DEPRECATED
     * -------------------------------------------------------------------------
     */
	public static function get_js()
	{
		$html = '<script type="text/javascript" src="%s"></script>';

		foreach(self::$_theme_meta['js'] as $js)
			echo sprintf($html, self::theme_url($js));
	}

    /**
     * -------------------------------------------------------------------------
     * Returns the full URL to the current views theme directory. This is 
	 * typically used to set the <base href="" /> tag in an html view.
     * -------------------------------------------------------------------------
     */
	public static function theme_url($path = null) {
		return Router::url(self::$_theme_dir . $path);
	}

	// TODO
	public static function theme_path() {
		return self::$_theme_path;
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
		if(!is_null(Caffeine::site_path()))
		{
			$site_path = Caffeine::site_path() . VIEW_DIR . $theme . '/';

			/*
			$site_path = CAFFEINE_SITES_PATH .
				Caffeine::site() . '/' .
				VIEW_DIR . $theme . '/';
			*/

			Debug::log('View', 'Checking site theme path: %s', $site_path);

			if(file_exists($site_path))
				$theme_path = $site_path;
		}

		// Either no site exists, or the site doesn't contain the theme, try
		// root views directory
		if(!$theme_path)
		{
			$view_path = VIEW_PATH . $theme . '/';
			Debug::log('View', 'Checking root theme path: %s', $view_path);

			if(file_exists($view_path))
				$theme_path = $view_path;
		}

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
			Debug::log('View', 'Setting theme path to: %s', $theme_path);
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
	 *
	 * @param $check_segments
	 *		Should we temporarily not check for views that match the current
	 *		segment? This will only work for "main" view loads, not loads being
	 *		called from within other views etc.
	 *
	 *		This is mostly just for use by the Path module which calls a 404
	 *		page. If segments is enabled, its possible the 404 page will never
	 *		be called and you'll just get a bunch of fucked up problems.
	 *
	 *		If you don't know what this is, just leave it alone. :P
     * -------------------------------------------------------------------------
     */
    public static function load($class, $block, $data = array(), $check_segments = true)
    {
		self::$_check_segments = $check_segments;

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
        Debug::log('View', 'Inserting view "%s"', $view_file);

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
		
		Debug::log('View', 'Outputting view');
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
    public static function callback_block_paths($class, $data) 
    {
        foreach($data as $c => $path)
        {
            Debug::log('View', 'Class "%s" setting block path to %s', 
                $c, $path);
            self::$_block_paths[strtolower($c)] = $path;
        }
    }
	*/
    
    /**
     * -------------------------------------------------------------------------
     * Renders the main theme view. This method goes through several steps when
	 * loading the view, providing the developer the ability to override
	 * specific view output.
	 *
	 * View files are HTML files located in a theme directory. The default
	 * theme directory can be set in the view configuration file.
	 *
	 * This method looks for the following views in order:
	 *	
	 *		1: Segment Check
	 *
	 *		A view with the same name as the current path segments separated
	 *		by underscores. All special characters are also replaced with 
	 *		underscores.
	 *
	 *		The check will start with all segments at first, but then remove
	 *		the last segment after each failed check until there are no more
	 *		segments. 
	 *
	 *		NOTE: If the last segment is the same as the module name loading
	 *		the current view, it will be ignored. The 3rd check will pick that
	 *		up.
	 *
	 *		Examples:
	 *
	 *		http://example.com/page/about-us
	 *			1. page_about_us.php
	 *
	 *		http://example.com/store/product/details/23
	 *			1. store_product_details_23.php
	 *			2. store_product_details.php
	 *			2. store_product.php
	 *
	 * 		--------------------------------------------------------------------
	 *
	 *		2: Block Check
	 *
	 *		A view with the same name as the main block being loaded. The
	 *		"main" block is the first block called using the View::load
	 *		method. It is typically called via a method that has been 
	 *		registered with a specific path.
	 * 		
	 * 		When loading a block as a view, the blocks data (variables) will be
	 * 		available within the view. This way you can manually re-create the blocks
	 * 		contents, or simple re-style the page and re-call the blocks callback
	 * 		manually.
	 *
	 *		@see View::load for more details on how blocks work.
	 *
	 *		Example:
	 *
	 *		View::load('Blog', 'blog_posts'); => blog_posts.php
	 *
	 * 		--------------------------------------------------------------------
	 *
	 *		3: Module Check
	 *
	 *		A view with the same name as the module loading the main block. 
	 *		
	 *		Examples:
	 *
	 *		View::load('Blog', 'blog_posts'); => blog.php
	 *		View::load('Photo_Gallery', 'albums') => photo_gallery.php
	 *
	 * 		--------------------------------------------------------------------
	 *
	 *		4: Default View
	 *
	 *		If no other views are found in the above checks, the default view
	 *		is loaded. The default view name is set in the views configuration
	 *		file. By default, it is "index.php".
     * -------------------------------------------------------------------------
     */
    private static function _render_view()
    {
		$view_path = self::_determine_view();
		self::$_check_segments = true; // Reset check segments per load
		Debug::log('View', 'Rendering view: %s', $view_path);
		return self::render($view_path, self::$_loaded_block['data']);
    }

    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
	private static function _determine_view()
	{
		// Get the module name loading the current view
		$current_module = Caffeine::class_module(self::$_loaded_block['class']);

		// 1: Check for view based on segments
		if(self::$_check_segments)
		{
			$paths = preg_replace('/[^A-Za-z0-9\/]/', '_', Router::current_path());
			$path_bits = explode('/', $paths);
			
			while($path_bits)
			{
				$path = implode('_', $path_bits);

				// Ignore empty paths and paths with same name as module
				if(strlen($path) && $path != $current_module)
				{
					$segment_path = self::$_theme_path . $path . CAFFEINE_EXT;
					Debug::log('View', 'Check for segment view: %s', $segment_path);

					if(file_exists($segment_path))
					{
						self::$_view_name = $path;
						return $segment_path;
					}
				}

				array_pop($path_bits);
			}
		}

		// 2: Check for view with block name
		$block_path = self::$_theme_path . $current_module . '_' . 
			str_replace('/', '_', self::$_loaded_block['block']) . CAFFEINE_EXT;

		Debug::log('View', 'Checking for block view: %s', $block_path);

		if(file_exists($block_path))
		{
			self::$_view_name = self::$_loaded_block['block'];
			return $block_path;
		}

		// 3: Check for view with module name
		$module_path = self::$_theme_path . $current_module . CAFFEINE_EXT;
		Debug::log('View', 'Checking for module view: %s', $module_path);

		if(file_exists($module_path))
		{
			self::$_view_name = $current_module;
			return $module_path;
		}

		// 4: Return default view
		Debug::log('View', 'No view overrides found, using default');
		return self::$_theme_path . VIEW_INDEX . CAFFEINE_EXT;
	}
    
    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
    private static function _render_block($class, $block, $data = array())
    {
        $class = strtolower($class);
		$block_path = self::_determine_block($class, $block);

		if($block_path)
		{
			Debug::log('View', 'Rendering block: %s', $block_path);
			return self::render($block_path, $data);
		}
		else
			Debug::log('View', 'No blocks found, not rendering.');
    }

    /**
     * -------------------------------------------------------------------------
     * TODO
     * -------------------------------------------------------------------------
     */
	private static function _determine_block($class, $block)
	{
		// First check for override block pre-pended with current view
		// If the block is within a directory, the view will be prepended to the
		// file, ignoring any directories
		$view_bits = explode('/', $block);
		$last_bit = count($view_bits) - 1;
		$view_bits[$last_bit] = self::$_view_name . '_' . $view_bits[$last_bit];
		$tmp_block = implode('/', $view_bits);

		$view_block = self::$_theme_blocks_path . $class . '/' . $tmp_block . CAFFEINE_EXT;
		Debug::log('View', 'Checking for view block: %s', $view_block);

		if(file_exists($view_block))
			return $view_block;
            
		// Secondly check for a regular override block
        $override_block = self::$_theme_blocks_path . $class . '/' . $block . CAFFEINE_EXT;
		Debug::log('View', 'Checking for override block: %s', $override_block);

        if(file_exists($override_block))
              return $override_block; 

		// Finally check for a block default set by the calling class
		if($module_path = Caffeine::module_path($class))
		{
			$default_block = $module_path . VIEW_BLOCKS_DIR . $block . CAFFEINE_EXT;
			Debug::log('View', 'Checking for default block: %s', $default_block);

			if(file_exists($default_block))
				return $default_block;
		}
		else
			Debug::log('View', 'No module path found for class: %s', $class);
            
		return false;
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
