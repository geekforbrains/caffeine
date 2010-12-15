<?php
/**
 * =============================================================================
 * Menu
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class Menu {

    private static $_sorted = array();
    
    public static function display()
	{
		self::$_sorted = self::_sort(Path::get_paths());
		echo self::_html(self::$_sorted);
    }

	private static function _html($sorted)
	{
		$html = '<ul>';

		foreach($sorted as $item)
		{
			$html .= '<li>';
			$html .= '<a href="' .Router::url($item['path']). '">';
			$html .= $item['title'];
			$html .= '</a>';

			if($item['children'])
				$html .= self::_html($item['children']);

			$html .= '</li>';
		}

		$html .= '</ul>';

		return $html;
	}
    
	private static function _sort($paths)
	{
		$sorted = array();

		foreach($paths as $path => $path_data)
		{
			$path_data['path'] = $path; // Make path avail to walk method

			if($path_data['visible'])
				$sorted = self::_walk($sorted, $path, $path_data);
		}

		return $sorted;
	}

	private static function _walk($sorted, $path, $path_data)
	{
		if(strstr($path, '/'))
		{
			$path_bits = explode('/', $path);
			$base = $path_bits[0];
			$child_path = implode('/', array_slice($path_bits, 1));
			
			if(!isset($sorted[$base]['children']))
				$sorted[$base]['children'] = array();
			
			$sorted[$base]['children'] = self::_walk(
				$sorted[$base]['children'], 
				$child_path,
				$path_data
			);
		}
		else
			$sorted[$path] = array(
				'title' => $path_data['title'],
				'path' => $path_data['path'],
				'children' => array()
			);

		return $sorted;
	}

}
