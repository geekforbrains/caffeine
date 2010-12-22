<?php
/**
 * =============================================================================
 * Menu
 * @author Gavin Vickery <gdvickery@gmail.com>
 * @version 1.0
 * =============================================================================
 */
class Menu {

	// Stores the sorted array to avoid sorting on every call
    private static $_sorted = array();

	/**
	 * -------------------------------------------------------------------------
	 * Used for building menu's based on registered paths. This method can be
	 * used to build full menus, or sub menus of different depths by specifying
	 * different parameters.
	 *
	 * @param $offset
	 *		This is the path offset you want the menu to start building from.
	 *		If nothing is specified, the menu will build from the root path.
	 *
	 *		To combine multiple offsets into a single menu, an array of offset
	 *		values can be passed. When using multiple offsets, the values are
	 *		added in the order they were specified. Duplicate values will be
	 *		overriden during the "merge".
	 *
	 * 		Example: 
	 *			Menu::build('blog');
	 *			Menu::build(array('blog', 'pages'));
	 *
	 *		The offset parameter also accepts "wildcards". These are "%s",
	 *		"%d" and "%". They represent patterns for strings, numbers or
	 *		anything respectively. Wildcards will be replaced by matching
	 *		segments in the current path.
	 *
	 * 		Note that using wildcards requires there be a URL path. To get a
	 *		menu regardless of the path, wildcards cannot be used.
	 *
	 *		Example:
	 *			Menu::build('admin/%s');
	 *
	 *			If the current path was "admin/users/manage" then the offset
	 *			"admin/%s" would be converted to "admin/users". However, if
	 *			the path was "admin/123" no offset would be set, because "%s"
	 *			specifies letters only.
	 *
	 * @param $depth
	 *		The depth param lets you specify how many sub-menu items deep you
	 *		want to go. By default, this value is -1, which means unlimited.
	 *		0 would be top level only, 1 would be the top level and one 
	 *		sub-item. This can be any numeric value. If the end of the menu is
	 *		reached before the max depth, the process stops and returns
	 *		silently.
	 *
	 *		Example:
	 *			Menu::build('blog/categories/%s', 0);
	 *
	 *			The above might get a top level menu of all sub-categories 
	 *			based on the category replaced by "%s". 
	 *
	 * @return mixed
	 *		Returns menu HTML if the menu items based on the given offset exist.
	 *		Otherwise "null" is returned.
	 * -------------------------------------------------------------------------
	 */
	public static function build($offset = null, $depth = -1, $attr = array())
	{
		$sorted = array();

		if(is_array($offset))
		{
			foreach($offset as $n)
			{
				$return = self::_offset($n, $depth);
				if($return)
					$sorted = array_merge($sorted, $return);
			}
		}
		else
			$sorted = self::_offset($offset, $depth);

		if($sorted)
			return self::_html($sorted, $attr);
		return null;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Handles sorting menu items based on the given offset. Also processes any
	 * wildcard values via the use of regex patterns.
	 * -------------------------------------------------------------------------
	 */
	private static function _offset($offset, $depth)
	{
		// Handle regex matches
		if(strstr($offset, '%'))
		{
			$path = Path::current();
			$regex = '@' . String::regify($offset) . '@';

			if(preg_match_all($regex, $path, $matches))
			{
				array_shift($matches);
				foreach($matches as $m)
					$offset = preg_replace('@%[s|d]?@', $m[0], $offset, 1);
			}
			else
				return null;
		}

		$sorted = self::_sort(Path::paths());
		$offset_bits = explode('/', $offset);

		// Move down the menu each bit at a time
		if(!is_null($offset))
			foreach($offset_bits as $bit)
				if(isset($sorted[$bit]))
					$sorted = $sorted[$bit]['children'];
				else
					return;

		// Now limit depth
		if($depth >= 0)
			$sorted = self::_depth($sorted, $depth);

		return $sorted;
	}

	/**
	 * -------------------------------------------------------------------------
	 * Limits the sorted menu array to the specified depth. Basically this
	 * crawls down each menu item moving into child items until the depth is
	 * reached.
	 * -------------------------------------------------------------------------
	 */
	private static function _depth($sorted, $depth)
	{
		if($depth < 0)
			return array();

		$new = array();
		foreach($sorted as $path => $item)
		{
			$new[$path] = $item;
			$new[$path]['children'] = self::_depth($item['children'], ($depth - 1));
		}

		return $new;
	}
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	private static function _html($sorted, $attr)
	{
		$html = '<ul';

		foreach($attr as $k => $v)
			$html .= ' '.$k.'="'.$v.'"';

		$html .= '>';

		foreach($sorted as $item)
		{
			$class = (stristr(Path::current(), $item['path'])) ? ' class="active"' : '';

			$html .= '<li' .$class. '>';
			$html .= '<a href="' .Router::url($item['path']). '">';
			$html .= strlen($class) ? '<strong>' : ''; 
			$html .= $item['title'];
			$html .= strlen($class) ? '</strong>' : ''; 
			$html .= '</a>';

			if($item['children'])
				$html .= self::_html($item['children'], $attr);

			$html .= '</li>';
		}

		$html .= '</ul>';

		return $html;
	}
    
	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
	private static function _sort($paths)
	{
		if(self::$_sorted)
			return self::$_sorted;

		$sorted = array();

		foreach($paths as $path => $path_data)
		{
			$path_data['path'] = $path; // Make path avail to walk method

			if($path_data['visible'])
				$sorted = self::_walk($sorted, $path, $path_data);
		}

		self::$_sorted = $sorted;
		return $sorted;
	}

	/**
	 * -------------------------------------------------------------------------
	 * TODO
	 * -------------------------------------------------------------------------
	 */
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
