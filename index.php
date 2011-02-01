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
 *      Gives classes a chance to set event priority.
 *
 * @event bootstrap
 *      Used to handle any pre-application processing.
 *
 * @event init
 *      Used as a main starting point for most libraries.
 *
 * @event cleanup
 *      Called at the end of the application for cleanup.
 * =============================================================================
 */
final class Caffeine {

	// Stores the current site "host" with subdomains
	private static $_site					= null;

	// Stores the path to the current site, if any
	private static $_site_path				= null;

	// Ensures Caffeine isn't re-initialized
	private static $_init                   = false;

	// Stores library priorities for core events
	private static $_priorities             = array();

	// Stores all event classes
	private static $_event_classes          = array();

	// Stores the module an autoloaded class belongs to
	private static $_class_modules			= array();

	// Stores debug messages
	private static $_debug                  = array();

	// An array of classes to ignore in debug
	private static $_debug_ignore           = array();

	// An array of classes to watch in debug, overrides ignore
	private static $_debug_watch            = array();

	/**
	 * -------------------------------------------------------------------------
	 * Returns the current sites domain without trailing slashes.
	 * -------------------------------------------------------------------------
	 */
	public static function get_site() {
		return self::$_site;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Gets the file path to the site folder, if any, in Caffeine's root.
	 * -------------------------------------------------------------------------
	 */
	public static function get_site_path() {
		return self::$_site_path;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Returns the name of the module the given class was loaded from.
	 * -------------------------------------------------------------------------
	 */
	public static function get_class_module($class) 
	{
		// Do a class check to autoload a class if it isn't already
		class_exists($class);

		$class = strtolower($class);

		if(isset(self::$_class_modules[$class]))
			return self::$_class_modules[$class];

		return false;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Starting point for Caffeine. Triggers a series of events for areas of the 
	 * application to implement. It is then up to the libraries to provide 
	 * functionality to the rest of the application.
	 *
	 * See the Caffeine::trigger method for more details.
	 * -------------------------------------------------------------------------
	 */
	public static function init()
	{
		if(CAFFEINE_DEBUG)
		{
			if(CAFFEINE_DEBUG_WATCH)
			{
				self::$_debug_watch = explode(',', strtolower(CAFFEINE_DEBUG_WATCH));
				foreach(self::$_debug_watch as $k => $v)
					self::$_debug_watch[$k] = trim($v);
			}
			elseif(CAFFEINE_DEBUG_IGNORE)
			{
				self::$_debug_ignore = explode(',', strtolower(CAFFEINE_DEBUG_IGNORE));
				foreach(self::$_debug_ignore as $k => $v)
					self::$_debug_ignore[$k] = trim($v);
			}
		}
		self::debug(1, 'Caffeine', 'Initializing');
				
		if(!self::$_init)
		{
			self::$_init = true;
			
			self::_determine_site();
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

				self::debug(3, 'Caffeine', 'Autoload trying path: %s', $site_path);

				if(file_exists($site_path))
				{
					self::debug(2, 'Caffeine', 'Autoloading from site: %s', $site_path);
					self::$_class_modules[$class] = $module_dir;
					require_once($site_path);
					return;
				}
			}


			$file_path = CAFFEINE_MODULES_PATH . $module_dir . '/' . $class . CAFFEINE_EXT;
			self::debug(3, 'Caffeine', 'Autoload trying path: %s', $file_path);
			
			if(file_exists($file_path))
			{
				self::debug(2, 'Caffeine', 'Autoloading from root: %s', $file_path);
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
		self::debug(1, 'Caffeine', 'The "%s" event was triggered by the "%s" class', 
			$event, $class);
		
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
					self::debug(2, 'Caffeine', 'Calling the "%s" event on the "%s" class', 
						$event_method, $event_class);
						
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
	 * -------------------------------------------------------------------------
	 */
	public static function error($num, $str, $file, $line)
	{
		printf('Error: %s<br />', $str);
		printf('File: %s<br />', $file);
		printf('Line: %d<br />', $line);
		self::debug();
		exit(1);
	}

	/**
	 * -------------------------------------------------------------------------
	 * Used to help debug and profile the application. Assists in seeing whats
	 * going on at each stage during a Caffeine's application life.
	 * -------------------------------------------------------------------------
	 */
	public static function debug()
	{
		if(!CAFFEINE_DEBUG)
			return;
			
		$timestamp = time();
		$args = func_get_args();
		
		if(func_num_args() >= 2)
		{
			$level = array_shift($args);
			$class = array_shift($args);
			$message = array_shift($args);
			
			if($level > CAFFEINE_DEBUG_VERBOSITY)
				return;
			
			if(self::$_debug_watch &&
				!in_array(strtolower($class), self::$_debug_watch))
				return;
			
			if(in_array(strtolower($class), self::$_debug_ignore))
				return;
			
			if(count($args) >= 1)
				$message = call_user_func_array('sprintf', 
					array_merge(array($message), $args));
					
			self::$_debug[] = array(
				'timestamp' => $timestamp,
				'level' => $level,
				'class' => $class,
				'message' => $message
			);
		}
		else
		{
			$debug = self::$_debug;
			
			$output = '<table id="caffeine-debug" border="1" cellpadding="2">';
			foreach($debug as $row)
			{
				$output .= '<tr>';
				$output .= '<td>' .$row['timestamp']. '</td>';
				$output .= '<td>' .$row['level']. '</td>';
				$output .= '<td>' .$row['class']. '</td>';
				$output .= '<td>' .$row['message']. '</td>';
				$output .= '</tr>';
			}
			$output .= '</table>';
			
			echo $output;
		}
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
		self::$_site = str_replace('www.', '', 
			strtolower($_SERVER['HTTP_HOST']));

		Caffeine::debug(1, 'Caffeine', 'Checking if the "%s" site directory
			exists', self::$_site);

		$path = CAFFEINE_SITES_PATH . self::$_site . '/';
		if(file_exists($path))
		{
			Caffeine::debug(2, 'Caffeine', 'Setting site path to: %s', $path);
			self::$_site_path = $path;
		}
	}

	/**
	 * -------------------------------------------------------------------------
	 * Scans all modules in the Caffeine modules directory for event and
	 * config files.
	 * -------------------------------------------------------------------------
	 */
	private static function _scan_modules()
	{
		Caffeine::debug(1, 'Caffeine', 'Scanning modules for configs and events classes');
		$modules = scandir(CAFFEINE_MODULES_PATH);
		
		foreach($modules as $module)
		{
			if($module{0} == '.')
				continue;
			
			// First check for config file, load if found
			$module_config = sprintf(CAFFEINE_CONFIG_FILE_FORMAT, $module);
			$config_path = CAFFEINE_MODULES_PATH . $module .'/'. $module_config . CAFFEINE_EXT;

			// Check for config override in sites module dir
			$site_config_path = self::$_site_path . CAFFEINE_MODULES_DIR . 
				$module .'/'. $module_config . CAFFEINE_EXT;

			if(file_exists($site_config_path))	
				$config_path = $site_config_path;
			
			self::debug(3, 'Caffeine', 'Checking if the "%s" module has a config file', $module);
			if(file_exists($config_path))
			{
				self::debug(2, 'Caffeine', 'Loading config file: %s', $config_path);
				require_once($config_path);
			}
			
			// Then check for events class
			$module_class = sprintf(CAFFEINE_EVENTS_FILE_FORMAT, $module);
			self::debug(3, 'Caffeine', 'Checking if the "%s" module has an events class', $module);
			
			if(class_exists($module_class))
				self::$_event_classes[] = strtolower($module_class);
		}
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

require_once('config.php');
error_reporting(CAFFEINE_ERROR_REPORTING);
date_default_timezone_set(CAFFEINE_TIMEZONE);

session_start();
$start = microtime(true);

Caffeine::init();

$end = microtime(true);
Caffeine::debug(1, 'System', 'Peak memory usage: %fMB', memory_get_peak_usage(true) / (1024 * 1024));
Caffeine::debug(1, 'System', 'Execution time: %f Seconds', ($end - $start));
Caffeine::debug(); // Output debug to browser, if enabled
