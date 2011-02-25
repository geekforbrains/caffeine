<?php
class SEO {

	private static $_path = array(
		'path' => null,
		'title' => null,
		'meta_author' => null,
		'meta_description' => null,
		'meta_keywords' => null,
		'meta_robots' => null
	);

	public static function check_path()
	{
		if($data = SEO_Model::get(Router::current_path()))
			self::$_path = $data;
	}

	public static function title($default, $prepend = null, $append = null)
	{
		if(strlen(self::$_path['title']))
			$default = $prepend . self::$_path['title'] . $append;

		elseif($data = Path::get_data(Router::current_path()))
			if(strlen($data['title']))
				$default = $prepend . $data['title'] . $append;

		return $default;
	}

	public static function meta($type, $content = null)
	{
		switch($type)
		{
			case 'author':
				if(strlen(self::$_path['meta_author']))
					if($content = self::$_path['meta_author'])
						return '<meta name="author" content="' .$content. '" />';

			case 'description':
				if(strlen(self::$_path['meta_description']))
					if($content = self::$_path['meta_description'])
						return '<meta name="description" content="' .$content. '" />';

			case 'keywords':
				if(strlen(self::$_path['meta_keywords']))
					if($content = self::$_path['meta_keywords'])
						return '<meta name="keywords" content="' .$content. '" />';

			case 'robots':
				if(strlen(self::$_path['meta_robots']))
					if($content = self::$_path['meta_robots'])
						return '<meta name="robots" content="' .$content. '" />';
		}

		return null;
	}

	public static function analytics()
	{
		View::load('SEO', 'analytics', 
			array('analytics' => SEO_Model::get_analytics()));
	}

}
