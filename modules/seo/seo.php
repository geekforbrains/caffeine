<?php
class SEO {

	private static $_path = null;
	private static $_default = null;

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function check_path()
	{
		if($default_data = SEO_Model::get_default_path())
			self::$_default = $default_data;

		if($path_data = SEO_Model::get_by_path(Router::current_path()))
			self::$_path = $path_data;
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function title()
	{
		$title = null;
		$using_default = false;
		$path_data = Path::get_path_data(); // Used for setting the title based on the current path

		if(!is_null(self::$_path) && strlen(self::$_path['title']))
			$title = self::$_path['title'];

		elseif(isset($path_data['title']) && strlen($path_data['title']))
			$title = $path_data['title'];

		elseif(!is_null(self::$_default))
		{
			$using_default = true;
			$title = self::$_default['title'];
		}

		// If we arent using default, append and prepend values to title, if any
		if(!$using_default && !is_null(self::$_default))
			$title = self::$_default['prepend'] . $title . self::$_default['append'];

		return $title;
	}
	
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function meta() 
	{
		$meta = array();

		if(!is_null(self::$_path))
		{
			foreach(self::$_path['meta'] as $m)
			{
				$key = strtolower($m['name']);
				$meta[$key] = $m;
			}
		}

		if(!is_null(self::$_default))
		{
			foreach(self::$_default['meta'] as $m)
			{
				$key = strtolower($m['name']);
				if(!isset($meta[$key]))
					$meta[$key] = $m;
			}
		}

		if($meta)
			View::load('SEO', 'meta', array('meta' => $meta));
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	public static function analytics()
	{
		View::load('SEO', 'analytics', 
			array('analytics' => SEO_Model::get_analytics()));
	}

}
