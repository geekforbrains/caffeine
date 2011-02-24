<?php
/**
 * =============================================================================
 * Caffeine
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 0.1
 *
 * Caffeine is a root class that triggers events in modules to form an 
 * application. Caffeine itself is rather stupid, its the modules that make it 
 * powerful.
 *
 * @event event_priority
 *		Used to set the calling priority for an event within a module.
 *
 * @event bootstrap
 *      Used to handle any pre-application processing.
 *
 * @event init
 *      Used as a main starting point for most modules.
 *
 * @event cleanup
 *      Called at the end of the application for cleanup.
 * =============================================================================
 */
final class Caffeine {

	// Ensures Caffeine isn't re-initialized
	private static $_init                   = false;

	// Stores the location of the main config path
	private static $_config_path			= CAFFEINE_CONFIG;

	// Stores the current site "host" with subdomains
	private static $_site					= null;

	// Stores the path to the current site, if any
	private static $_site_path				= null;

	// Stores the path to the current files dir
	private static $_files_path				= null;

	// Stores library priorities for core events
	private static $_priorities             = array();

	// Stores all event classes
	private static $_event_classes          = array();

	// Stores the module an autoloaded class belongs to
	private static $_class_modules			= array();

	// A list of modules to be ignored during loading
	// @see Caffeine::_disabled
	private static $_disabled_modules		= array();

	/**
	 * -------------------------------------------------------------------------
	 * Returns the current sites domain without trailing slashes.
	 * -------------------------------------------------------------------------
	 */
	public static function site() {
		return self::$_site;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Gets the file path to the site folder, if any, in Caffeine's root.
	 * -------------------------------------------------------------------------
	 */
	public static function site_path() {
		return self::$_site_path;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Gets the current files path.
	 * -------------------------------------------------------------------------
 	 */
	public static function files_path() {
		return self::$_files_path;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Returns the name of the module the given class was loaded from.
	 * -------------------------------------------------------------------------
	 */
	public static function class_module($class) 
	{
		if(class_exists($class))
		{
			$class = strtolower($class);

			if(isset(self::$_class_modules[$class]))
				return self::$_class_modules[$class];
		}

		return false;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Determines which main config.php file to load based on the current site.
	 * If a config exists for the current site (ex: sites/mysite/config.php)
	 * that file will be loaded. Otherwise, the default config will be loaded)
	 * -------------------------------------------------------------------------
	 */
	public static function config()
	{
		self::_determine_site();
		self::_determine_file_path();

		if(!is_null(self::$_site))
		{
			$config_path = CAFFEINE_SITES_PATH . 
				self::$_site . '/' . CAFFEINE_CONFIG;

			if(file_exists($config_path))
				self::$_config_path = $config_path;
		}

		return self::$_config_path;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Starting point for Caffeine. Triggers a series of events for areas of the 
	 * application to implement. It is then up to the modules to provide 
	 * functionality to the rest of the application.
	 *
	 * See the Caffeine::trigger method for more details.
	 * -------------------------------------------------------------------------
	 */
	public static function init()
	{
		if(!self::$_init)
		{
			self::$_init = true;

			// Check for disabled modues
			if(strlen(CAFFEINE_DISABLED_MODULES))
			{
				$bits = explode(',', CAFFEINE_DISABLED_MODULES);
				foreach($bits as $bit)
					self::$_disabled_modules[] = strtolower(trim($bit));
			}

			self::_scan_modules();

			self::trigger('Caffeine', 'event_priority');
			self::trigger('Caffeine', 'bootstrap');
			self::trigger('Caffeine', 'init');
			self::trigger('Caffeine', 'cleanup');
		}
		else
			trigger_error('Caffeine should never be re-initialized!', E_USER_ERROR);
	}

	/**
	 * -------------------------------------------------------------------------
	 * Handles autoloading module files. Called by PHP's magic __autoload
	 * function.
	 *
	 * First checks to see if a site directory exists and if the module can be
	 * found in there. Otherwise, load from the root directories.
	 * -------------------------------------------------------------------------
	 */
	public static function autoload($class)
	{
		$class = strtolower($class);
		$class_bits = explode('_', $class);

		$module_bits = $class_bits;
		while($module_bits)
		{
			$module_dir = implode('_', $module_bits);

			// If a site directory exists, check for modules there first
			if(!is_null(self::$_site_path))
			{
				$site_path = CAFFEINE_SITES_PATH . 
					self::$_site . '/' .
					CAFFEINE_MODULES_DIR .
					$module_dir . '/' . $class . CAFFEINE_EXT;

				if(file_exists($site_path))
				{
					self::$_class_modules[$class] = $module_dir;
					require_once($site_path);
					return;
				}
			}

			$file_path = CAFFEINE_MODULES_PATH . $module_dir . '/' . $class . CAFFEINE_EXT;
			
			if(file_exists($file_path))
			{
				self::$_class_modules[$class] = $module_dir;
				require_once($file_path);
				return;
			}
			
			array_pop($module_bits);
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * Triggers a Caffeine event. Events allow other areas of the application to
	 * respond by implementing the events method name in its class. By default,
	 * Caffeine modules use the "<Module>_Events" class and file name.
	 *
	 * An events method name is the combination of the calling class and event 
	 * name. For example, if the class "Foo" triggered the event "bar", the
	 * event method would be: "foo_bar()".
	 *
	 * In addition to events, the caller class can implement a "callback"
	 * method that will be called each time an event method completes. So if the 
	 * class "Foo" called the event "bar", the "Foo" class could implement the 
	 * method "callback_bar()". The first parameter passed to the callback 
	 * method is the class name responding to the event, the second parameter is 
	 * an array of data the event returned, if any.
	 *
	 * @param $class
	 *      The class name triggering the event.
	 *
	 * @param $event
	 *      The event name being triggered.
	 *
	 * @param $data
	 *      An array of data sent to any classes implementing the event.     
	 * -------------------------------------------------------------------------
	 */
	public static function trigger($class, $event, $data = array())
	{
		$event_method = strtolower($class) .'_'. $event;
		$event_callback = sprintf(CAFFEINE_EVENT_CALLBACK, $event);
			
		$sorted = array();
		if(isset(self::$_priorities[$event_method]))
		{
			$tmp = self::$_priorities[$event_method];
			unset($tmp['set']);
			
			foreach(self::$_event_classes as $class)
				if(!in_array($class, self::$_priorities[$event_method]['set']))
					$tmp[CAFFEINE_DEFAULT_EVENT_PRIORITY][] = $class;
				   
			ksort($tmp); 
			$sorted = $tmp;
		}
		else
			$sorted = array(CAFFEINE_DEFAULT_EVENT_PRIORITY => self::$_event_classes);

		foreach($sorted as $event_classes)
		{
			foreach($event_classes as $event_class)
			{
				if(method_exists($event_class, $event_method))
				{
					$data = call_user_func(array($event_class, $event_method), $data);
					
					if(method_exists($class, $event_callback))
						$data = call_user_func(array($class, $event_callback), 
							$event_class, $data);
				}
			}
		}
		
		return $data;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Error handler for errors triggered via PHP's trigger_error function.
	 *
	 * TODO Move this to its own module, and use exceptions instead.
	 * -------------------------------------------------------------------------
	 */
	public static function error($num, $str, $file, $line)
	{
		printf('Error: %s<br />', $str);
		printf('File: %s<br />', $file);
		printf('Line: %d<br />', $line);
		
		echo '<pre>';
		debug_print_backtrace();
		echo '</pre>';

		exit(1);
	}

	/**
	 * -------------------------------------------------------------------------
	 * Callback for the Caffeine::event_priority event.
	 * -------------------------------------------------------------------------
	 */
	private static function callback_event_priority($class, $data)
	{
		foreach($data as $event => $priority)
		{  
			self::$_priorities[$event]['set'][] = $class; // Used for quicker comparisons
			self::$_priorities[$event][$priority][] = $class;
		}
	}

	/**
	 * ------------------------------------------------------------------------
	 * Checks if a directory with the current domain name exists in the "sites"
	 * directory. If it does, the current site is set to that directory name.
	 *
	 * If a site exists, all modules and views will first be loaded from within
	 * the sites directory. If the module or view doesn't exist, its loaded
	 * from the default root directories.
	 * ------------------------------------------------------------------------
	 */
	private static function _determine_site()
	{
		$site = str_replace('www.', '', 
			strtolower($_SERVER['HTTP_HOST']));

		$path = CAFFEINE_SITES_PATH . $site . '/';

		// Site info should only be set if a site directory exists
		if(file_exists($path))
		{
			self::$_site = $site;
			self::$_site_path = $path;
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * Determines the current upload path based on the current site, and if
	 * a site directory exists.
	 * 
	 * Also runs checks to make sure the file directory exists, and is 
	 * writable.
	 * -------------------------------------------------------------------------
	 */
	private static function _determine_file_path()
	{
		// Default files to root file directory
		$file_path = CAFFEINE_FILES_PATH;	

		// If a site directory exists for the current site, set the file path there
		if(!is_null(self::$_site) && file_exists(CAFFEINE_SITES_PATH . self::$_site))
			$file_path = CAFFEINE_SITES_PATH . self::$_site . '/' . CAFFEINE_FILES_DIR;

		if(!file_exists($file_path))
			die('The files directory doesn\'t exist: ' . $file_path);

		if(!is_writable($file_path))
			die('The files directory isn\'t writable: ' . $file_path);

		self::$_files_path = $file_path;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Scans all modules in the Caffeine modules directory for event and
	 * config files.
	 * -------------------------------------------------------------------------
	 */
	private static function _scan_modules()
	{
		$modules = scandir(CAFFEINE_MODULES_PATH);
		
		foreach($modules as $module)
		{
			if($module{0} == '.')
				continue;

			if(self::_is_disabled($module))
				continue;
			
			// First check for config file, load if found
			$module_config = sprintf(CAFFEINE_CONFIG_FILE_FORMAT, $module);
			$config_path = CAFFEINE_MODULES_PATH . $module .'/'. $module_config . CAFFEINE_EXT;

			// Check for config override in sites module dir
			$site_config_path = self::$_site_path . CAFFEINE_MODULES_DIR . 
				$module .'/'. $module_config . CAFFEINE_EXT;

			if(file_exists($site_config_path))	
				$config_path = $site_config_path;
			
			if(file_exists($config_path))
				require_once($config_path);
			
			// Then check for events class
			$module_class = sprintf(CAFFEINE_EVENTS_FILE_FORMAT, $module);
			
			if(class_exists($module_class))
				self::$_event_classes[] = strtolower($module_class);
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * Determines if the given module has been disabled via the 
	 * CAFFEINE_DISABLED_MODULES configuration.
	 * -------------------------------------------------------------------------
	 */
	private static function _is_disabled($module)
	{
		if(self::$_disabled_modules)
			if(in_array($module, self::$_disabled_modules))
				return true;
		return false;
	}

}


// Set Caffeine to handle autoloading
function __autoload($class) {
    Caffeine::autoload($class);
}

// Set Caffeine to handle errors
function caffeine_error($num, $str, $file, $line) {
    Caffeine::error($num, $str, $file, $line);
}
set_error_handler('caffeine_error');

require_once('constants.php');
require_once(Caffeine::config());
session_start();

Caffeine::init();
